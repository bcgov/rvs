
export const globalMixins = {
    methods: {
        $getFormattedDate() {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        },
        $getProgramNameFromGuid(programs, program_guid) {
            let name = '';
            programs.forEach(program => {
                if (program.guid === program_guid) {
                    name = program.program_name;
                }
            });
            return name;
        },
        $getYesNo(value) {
            return value == true ? 'Yes' : 'No';
        },
        $back: function()
        {
            window.history.back();
        },
        $formatMoney(
            value,
            { locale = 'en-CA', currency = 'CAD' } = {}
        ) {
            if (value === null || value === undefined || isNaN(value)) return '';
            return new Intl.NumberFormat(locale, {
                style: 'currency',
                currency,
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }).format(Number(value));
        }
    }
};
