<template>

</template>
<script>
import { defineComponent, h } from 'vue'

import { Line } from 'vue-chartjs'
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    LineElement,
    LinearScale,
    PointElement,
    CategoryScale,
    Plugin
} from 'chart.js'

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    LineElement,
    LinearScale,
    PointElement,
    CategoryScale
)

export default defineComponent({
    name: 'ReportChart',
    components: {
        Line
    },
    props: {
        results: Object,
        title: String,
        bg: String,
        chartId: {
            type: String,
            default: 'line-chart'
        },
        width: {
            type: Number,
            default: 400
        },
        height: {
            type: Number,
            default: 400
        },
        cssClasses: {
            default: '',
            type: String
        },
        styles: {
            type: Object,
            default: () => {}
        },
        plugins: {
            type: Array,
            default: () => []
        }
    },
    setup(props) {
        let totals = [];
        for(let i=0; i<Object.values(props.results).length; i++){
            totals.push(Object.values(props.results)[i].TOTAL);
        }
        const chartData = {
            labels: Object.keys(props.results),
            datasets: [
                {
                    label: props.title,
                    backgroundColor: props.bg,
                    data: totals
                }
            ]
        }

        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false
        }

        return () =>
            h(Line, {
                chartData,
                chartOptions,
                chartId: props.chartId,
                width: props.width,
                height: props.height,
                cssClasses: props.cssClasses,
                styles: props.styles,
                plugins: props.plugins
            })
    }
});



</script>
