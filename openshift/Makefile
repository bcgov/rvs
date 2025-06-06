## get values from Github actions yml file. under env
export GIT_LOCAL_BRANCH?=$(shell git rev-parse --abbrev-ref HEAD)
export NAMESPACE := $(or $(NAMESPACE), 06cb9b-dev)
export APP_NAME := $(or $(PROJECT_NAME), rvs)
export BUILD_ID := $(or $(BUILD_ID), 1)
export BUILD_REF := $(or $(BUILD_REF), $(GIT_LOCAL_BRANCH))
export LICENSE_PLATE := $(or $(LICENSE_PLATE), 06cb9b)
export LICENSE_BRANCH := $(or $(LICENSE_BRANCH), nonprod)
export BUILD_NAMESPACE := $(or $(BUILD_NAMESPACE), 06cb9b-tools)
export DOMAIN := $(or $(DOMAIN), apps.silver.devops.gov.bc.ca)
export HOST_PREFIX := $(or $(HOST_PREFIX), rvs)
export SOURCE_REPOSITORY_BRANCH := $(or $(SOURCE_REPOSITORY_BRANCH), dev)
export BACKUP_POSTGRESQL_APP_NAME := $(or $(BACKUP_POSTGRESQL_APP_NAME), rvs-patroni-backup)
export BACKUP_CONFIGMAP_NAME := $(or $(BACKUP_CONFIGMAP_NAME), rvs-patroni-backup-conf)

#if
ifeq ($(GIT_LOCAL_BRANCH),dev)
define OC_USER_ID
"1009370000"
endef
define PARAM_FILE
"dev.env"
endef
define BUILD_TAG
"latest-dev"
endef
else
define OC_USER_ID
"1009380000"
endef
define PARAM_FILE
"prod.env"
endef
define BUILD_TAG
"latest-prod"
endef
endif


#define general variables
define LABEL_NAME
"RVS"
endef



define HOST
"$(HOST_PREFIX)-$(BUILD_NAMESPACE).apps.silver.devops.gov.bc.ca.$(DOMAIN)"
endef

define SOURCE_REPOSITORY_REF
"https://github.com/bcgov/rvs"
endef


define rollout_and_wait
@oc -n $(NAMESPACE) rollout status $1
endef

oc-build-web: | print-status build-web
oc-deploy-web: | print-status deploy-web
oc-build-patroni: | print-status build-patroni
oc-deploy-patroni: | print-status deploy-patroni
oc-build-cronjob: | print-status build-cronjob

print-status:
	@echo " +---------------------------------------------------------+ "
	@echo " | Current Settings										| "
	@echo " +---------------------------------------------------------+ "
	@echo " | PROJECT:	  			$(APP_NAME)"
	@echo " | BRANCH:	   			$(BUILD_REF)"
	@echo " | NAMESPACE:					$(NAMESPACE)"
	@echo " | BUILD_NAMESPACE:				$(BUILD_NAMESPACE)"
	@echo " | GIT_LOCAL_BRANCH:				$(GIT_LOCAL_BRANCH)"
	@echo " +---------------------------------------------------------+ "
	@echo " | BUILD_ID:						$(BUILD_ID) |"
	@echo " | BUILD_TAG:						$(BUILD_TAG) |"
	@echo " | HOST: 	$(HOST) |"
	@echo " | NAME:					$(HOST_PREFIX) |"
	@echo " | REPO_NAME: 		$(SOURCE_REPOSITORY_REF) |"
	@echo " | REPO_BRANCH:						$(SOURCE_REPOSITORY_BRANCH) |"
	@echo " | OC_USER_ID: 					$(OC_USER_ID) |"
	@echo " | PARAM_FILE: 					$(PARAM_FILE) |"
	@echo " | PARAM_FILE: 					$(PARAM_FILE) |"
	@echo " | LICENSE_PLATE: 				$(LICENSE_PLATE) |"

#	"$(HOST_PREFIX)-$(BUILD_NAMESPACE).apps.silver.devops.gov.bc.ca.$(DOMAIN)"
	@echo " +---------------------------------------------------------+ "

build-web:
	test -n "$(BUILD_REF)"
	test -n "$(BUILD_NAMESPACE)"
	@echo "+\n++ PROCESSING Web\n+"
	@oc -n $(BUILD_NAMESPACE) process -f bc.yaml --param-file=$(PARAM_FILE) | oc -n $(BUILD_NAMESPACE) apply -f -
	@echo "+\n++ BUILDING Web\n+"
	@oc -n $(BUILD_NAMESPACE) start-build bc/$(HOST_PREFIX) --wait
	@echo "+\n++ FINISHED BUILDING Web\n+"

deploy-web:
	@echo "+\n++ START DEPLOYING Web\n+"
	test -n "$(NAMESPACE)"
	test -n "$(BUILD_NAMESPACE)"
	@echo "+\n++ Deploying Web into $(NAMESPACE)\n+"
	@oc -n $(NAMESPACE) process -f dc_latest.yaml --param-file=$(PARAM_FILE) | oc -n $(NAMESPACE) apply -f -
	$(call rollout_and_wait,dc/$(HOST_PREFIX))
	@echo "+\n++ FINISHED Deploying Web\n+"

build-patroni:
	@oc -n $(BUILD_NAMESPACE) process -f patroni-build.yaml -p NAME=rvs-patroni -p TAG="12.4-latest" -p BASE_IMAGE_NAME=postgres -p BASE_IMAGE_TAG="12.4" -p SOURCE_REPOSITORY_REF="main" -p SOURCE_CONTEXT_DIR="apps/pgsql/patroni/docker" | oc -n $(BUILD_NAMESPACE) apply -f -
	@oc -n $(BUILD_NAMESPACE) start-build bc/rvs-patroni --wait

deploy-patroni:
	@oc -n $(NAMESPACE) process -f patroni-deploy.yaml -p LABEL_NAME=$(LABEL_NAME) -p BUILD_NAMESPACE=$(BUILD_NAMESPACE) -p IMAGE_STREAM_TAG=patroni:12-latest | oc -n $(NAMESPACE) apply -f -

# Create the POSTGRES structure
db-backup-build-postgresql:
	test -n "$(BUILD_NAMESPACE)"
	test -n "$(BACKUP_POSTGRESQL_APP_NAME)"
	@echo "+\n++ Creating build resources for database backups on $(BUILD_NAMESPACE)\n+"
	oc -n $(BUILD_NAMESPACE) process -f backup-build.yaml \
		-p BACKUP_POSTGRESQL_APP_NAME=$(BACKUP_POSTGRESQL_APP_NAME) OUTPUT_IMAGE_TAG=v1 \
		-p BASE_IMAGE_FOR_BUILD=artifacts.developer.gov.bc.ca/docker-remote/centos/postgresql-12-centos7:20200917-804ef01 \
		| oc -n $(BUILD_NAMESPACE) apply -f -
	@oc -n $(BUILD_NAMESPACE) start-build bc/rvs-patroni-backup --wait

db-backup-deploy-postgresql:
	test -n "$(NAMESPACE)"
	test -n "$(BUILD_NAMESPACE)"
	test -n "$(BACKUP_POSTGRESQL_APP_NAME)"
	@echo "+\n++ Creating deploy config resoures for database backups on $(NAMESPACE)\n+"
	oc -n $(NAMESPACE) process -f backup-deploy.yaml \
  	-p BACKUP_POSTGRESQL_APP_NAME=$(BACKUP_POSTGRESQL_APP_NAME) \
  	-p IMAGE_NAMESPACE=$(BUILD_NAMESPACE) \
  	-p SOURCE_IMAGE_NAME=$(BACKUP_POSTGRESQL_APP_NAME) \
  	-p TAG_NAME=v1 \
		-p FTP_SECRET_KEY=$(BACKUP_POSTGRESQL_APP_NAME)-ftp-secret \
  	-p BACKUP_VOLUME_NAME=$(BACKUP_POSTGRESQL_APP_NAME)-pvc \
		-p BACKUP_VOLUME_SIZE=20Gi \
  	-p VERIFICATION_VOLUME_SIZE=5Gi \
  	-p ENVIRONMENT_NAME=$(NAMESPACE) \
		-p CONFIG_MAP_NAME=$(BACKUP_CONFIGMAP_NAME) \
		-p VERIFICATION_VOLUME_NAME=$(BACKUP_POSTGRESQL_APP_NAME)-verification-pvc \
		-p DATABASE_SERVER_NAME=PATRONI_MASTER \
		-p DATABASE_SECRET_NAME=patroni-creds \
		-p DATABASE_SECRET_USER_KEY_NAME=superuser-username \
		-p DATABASE_SECRET_PASSWORD_KEY_NAME=superuser-password \
  	-p ENVIRONMENT_FRIENDLY_NAME='$(NAMESPACE) POSTGRESQL DB Backups' \
		| oc -n $(NAMESPACE) apply -f -


build-cronjob:
	@echo "+\n++ PROCESSING CronJob\n+"
	@oc -n $(BUILD_NAMESPACE) process -f bc_cron.yaml --param-file=$(PARAM_FILE) | oc -n $(BUILD_NAMESPACE) apply -f -
	@echo "+\n++ BUILDING CronJob\n+"
	@oc -n $(BUILD_NAMESPACE) start-build bc/$(HOST_PREFIX)-cron --wait
	@echo "+\n++ FINISHED BUILDING CronJob\n+"

