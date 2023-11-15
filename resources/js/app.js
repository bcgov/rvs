import './bootstrap';

//import '../css/app.css'
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

// @ts-ignore
const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'AdonisJS';

createInertiaApp({
    id: 'app',
    title: (title) => `${appName} - ${title}`,
    // eslint-disable-next-line @typescript-eslint/ban-ts-comment
    // @ts-ignore
    resolve: (name) => {
        let parts = name.split('::')
        let type = false;
        if (parts.length > 1) type = parts[0]
        if(type) return require(`../../Modules/${type}/Resources/assets/js/Pages/${parts[1]}.vue`).default
        return require(`./Pages/${name}.vue`).default
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mixin({ methods: {
                    generateTestValues
                }
            })
            .mount(el);

    },
})



const generateRandomStr = function (){
    return (Math.random() + 1).toString(36).substring(2);
}
const generateRandomInt = function (len= 0){
    return (Math.random() + 1).toString().substring(2).substring(len);
}
const generateRandomDate = function (date=Date.now()){
    return new Date(date).toLocaleDateString('en-CA');
}
const generateRandomEmail = function (){
    return generateRandomStr() + "@" + generateRandomStr() + ".com";
}
const generateTestValues = function (){

    // set the text values
    let textInputs = document.querySelectorAll('[data-test="true"] input[type="text"]');
    for(let i=0; i<textInputs.length; i++){
        textInputs[i].value = generateRandomStr();
    }
    // set the number values
    let numberInputs = document.querySelectorAll('[data-test="true"] input[type="number"]');
    for(let i=0; i<numberInputs.length; i++){
        numberInputs[i].value = generateRandomInt();
    }
    // set the number values
    let dateInputs = document.querySelectorAll('[data-test="true"] input[type="date"]');
    for(let i=0; i<dateInputs.length; i++){
        dateInputs[i].value = generateRandomDate();
    }
    // set the email values
    let emailInputs = document.querySelectorAll('[data-test="true"] input[type="email"]');
    for(let i=0; i<emailInputs.length; i++){
        emailInputs[i].value = generateRandomEmail();
    }
    // set the select values
    let selectInputs = document.querySelectorAll('[data-test="true"] select');
    for(let i=0; i<selectInputs.length; i++){
        selectInputs[i].value = selectInputs[i].children[1].value;
    }
}
