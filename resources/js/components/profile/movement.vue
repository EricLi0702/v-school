<template>
    <div class="chart">
        <div class="bg" style="background-image: url('/img/icon/bg.287213dc.png');">
            <div class="cc">
                <div class="desc userName hidePoint hideArrow">
                    {{month}}月{{today}}日
                </div>
            </div>
            <div class="block-element el-dropdown"></div>
            <div class="sg">
                <div id="sg">
                    <div id="highcharts-ysbrv11-0" dir="ltr" class="highcharts-container " style="position: relative; overflow: hidden; width: 240px; height: 252px; text-align: left; line-height: normal; z-index: 0; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);"><svg version="1.1" class="highcharts-root" style="font-family:&quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, Arial, Helvetica, sans-serif;font-size:12px;" xmlns="http://www.w3.org/2000/svg" width="240" height="252" viewBox="0 0 240 252"><desc>Created with Highcharts 8.1.0</desc><defs><clipPath id="highcharts-ysbrv11-2-"><rect x="0" y="0" width="220" height="227" fill="none"></rect></clipPath></defs><rect fill="transparent" class="highcharts-background" x="0" y="0" width="240" height="252" rx="0" ry="0"></rect><rect fill="none" class="highcharts-plot-background" x="10" y="10" width="220" height="227"></rect><g class="highcharts-pane-group" data-z-index="0"><path fill="rgba(255,255,255,.2)" d="M 120 18.78 A 104.72 104.72 0 1 1 119.89528001745327 18.780052359995636 M 119.89995501667411 23.455050022495826 A 100.045 100.045 0 1 0 120 23.455" class="highcharts-pane "></path></g><g class="highcharts-grid highcharts-yaxis-grid" data-z-index="1"></g><rect fill="none" class="highcharts-plot-border" data-z-index="1" x="10" y="10" width="220" height="227"></rect><g class="highcharts-axis highcharts-yaxis" data-z-index="2"><path fill="none" class="highcharts-axis-line" data-z-index="7" d="M 120 30 A 93.5 93.5 0 1 1 119.90650001558329 30.000046749996102 M 120 123.5 A 0 0 0 1 0 120 123.5"></path></g><g class="highcharts-series-group" data-z-index="3"><g data-z-index="0.1" class="highcharts-series highcharts-series-0 highcharts-solidgauge-series highcharts-tracker" transform="translate(10,10) scale(1 1)" clip-path="url(#highcharts-ysbrv11-2-)"><path fill="#fff" d="M 110 9.280000000000001 A 104.72 104.72 0 0 1 109.89528001745335 9.280052359995636 L 109.89995501667418 13.955050022495826 A 100.045 100.045 0 0 0 110 13.954999999999998 Z" sweep-flag="0" stroke-linecap="round" stroke-linejoin="round" class="highcharts-point highcharts-color-0"></path></g><g data-z-index="0.1" class="highcharts-markers highcharts-series-0 highcharts-solidgauge-series" transform="translate(10,10) scale(1 1)" clip-path="none"></g></g><text x="120" text-anchor="middle" class="highcharts-title" data-z-index="4" style="color:#333333;font-size:18px;fill:#333333;" y="24"></text><text x="120" text-anchor="middle" class="highcharts-subtitle" data-z-index="4" style="color:#666666;fill:#666666;" y="24"></text><text x="10" text-anchor="start" class="highcharts-caption" data-z-index="4" style="color:#666666;fill:#666666;" y="249"></text><g class="highcharts-legend" data-z-index="7"><rect fill="none" class="highcharts-legend-box" rx="0" ry="0" x="0" y="0" width="8" height="8" visibility="hidden"></rect><g data-z-index="1"><g></g></g></g><g class="highcharts-axis-labels highcharts-yaxis-labels" data-z-index="7"></g></svg></div>
                </div>
                <div class="overlay">
                    <div class="h1 step">0</div>
                    <div class="h2">步</div>
                </div>
                <div class="h1">
                    <span class="kilometre">0</span>公里 
                    <span class="Calorie">0</span>卡路里 
                    <span class="state"></span>未达标
                </div>
            </div> 
        </div>
        <div class="flex jac">
            <RadioGroup v-model="type">
                <Radio label="周"></Radio>
                <Radio label="月"></Radio>
            </RadioGroup>
        </div>
        <div id="line">
            <div id="highcharts-1k89is1-108" style="position: relative; overflow: hidden; width: 680px; height: 222px; text-align: left; line-height: normal; z-index: 0; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);" dir="ltr" class="highcharts-container "><svg version="1.1" class="highcharts-root" style="font-family:&quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, Arial, Helvetica, sans-serif;font-size:12px;" xmlns="http://www.w3.org/2000/svg" width="680" height="222" viewBox="0 0 680 222"><desc>Created with Highcharts 8.1.0</desc><defs><clipPath id="highcharts-1k89is1-109-"><rect x="0" y="0" width="660" height="156" fill="none"></rect></clipPath></defs><rect fill="#fff7f4" class="highcharts-background" x="0" y="0" width="680" height="222" rx="0" ry="0"></rect><rect fill="none" class="highcharts-plot-background" x="10" y="10" width="660" height="156"></rect><g class="highcharts-pane-group" data-z-index="0"></g><g class="highcharts-grid highcharts-xaxis-grid" data-z-index="1"></g><g class="highcharts-grid highcharts-yaxis-grid" data-z-index="1"></g><rect fill="none" class="highcharts-plot-border" data-z-index="1" x="10" y="10" width="660" height="156"></rect><g class="highcharts-axis highcharts-xaxis" data-z-index="2"><path fill="none" class="highcharts-axis-line" data-z-index="7" d="M 10 166 L 670 166"></path></g><g class="highcharts-axis highcharts-yaxis" data-z-index="2"><path fill="none" class="highcharts-axis-line" data-z-index="7" d="M 10 10 L 10 166"></path></g><g class="highcharts-series-group" data-z-index="3"><g data-z-index="0.1" class="highcharts-series highcharts-series-0 highcharts-spline-series highcharts-color-0" transform="translate(10,10) scale(1 1)" clip-path="url(#highcharts-1k89is1-109-)"></g><g data-z-index="0.1" class="highcharts-markers highcharts-series-0 highcharts-spline-series highcharts-color-0" transform="translate(10,10) scale(1 1)"></g></g><text x="340" text-anchor="middle" class="highcharts-title" data-z-index="4" style="color:#333333;font-size:18px;fill:#333333;" y="24"></text><text x="340" text-anchor="middle" class="highcharts-subtitle" data-z-index="4" style="color:#666666;fill:#666666;" y="24"></text><text x="10" text-anchor="start" class="highcharts-caption" data-z-index="4" style="color:#666666;fill:#666666;" y="219"></text><g class="highcharts-legend" data-z-index="7" transform="translate(309,178)"><rect fill="none" class="highcharts-legend-box" rx="0" ry="0" x="0" y="0" width="62" height="29" visibility="visible"></rect><g data-z-index="1"><g><g class="highcharts-legend-item highcharts-spline-series highcharts-color-0 highcharts-series-0" data-z-index="1" transform="translate(8,3)"><path fill="none" d="M 0 11 L 16 11" class="highcharts-graph" stroke="#7cb5ec" stroke-width="2"></path><path fill="#ff6a6b" d="M 8 15 A 4 4 0 1 1 8.003999999333336 14.999998000000167 Z" class="highcharts-point" opacity="1"></path><text x="21" style="color:#333333;cursor:pointer;font-size:12px;font-weight:bold;fill:#333333;" text-anchor="start" data-z-index="2" y="15"><tspan>步数</tspan></text></g></g></g></g><g class="highcharts-axis-labels highcharts-xaxis-labels" data-z-index="7"></g><g class="highcharts-axis-labels highcharts-yaxis-labels" data-z-index="7"></g></svg></div>
        </div>
        <div class="block col mt10">
            <div>
                <div class="block col mt10">本周共计：0公里，0卡</div>
                <div class="block col mt10">本周日均：0.0步，0.0卡</div>
                <div class="block col mt10">最高单日：-月-日，0步，0卡</div>
                <div class="block col mt10">最低单日：-月-日，0步，0卡</div>
                <div class="block col mt10">累计达标天数：3500.0天</div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            month:'',
            today:'',
            type:'周'
        }
    },
    mounted(){
        let d = new Date()
        this.month = d.getMonth()+1
        this.today =d.getDate()
    }
}
</script>

<style>

</style>