<script>
  import { Pie } from 'vue-chartjs'
  import labels from 'chartjs-plugin-datalabels';
  export default {
    extends: Pie,
    props:['data'],
    data () {
      return {
        chartData: {
          labels:[],
          datasets: [{
              borderWidth: 1,
              borderColor: [
              'rgba(255, 99, 132, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(255, 206, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)',
              'rgba(153, 102, 255, 0.2)',
              'rgba(255, 159, 64, 0.2)',
              'rgba(255, 99, 132, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(255, 206, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)',
              'rgba(153, 102, 255, 0.2)',
              'rgba(255, 159, 64, 0.2)'        
              ],
              backgroundColor: [
              'rgba(255, 99, 132, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(255, 206, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)',
              'rgba(153, 102, 255, 0.2)',
              'rgba(255, 159, 64, 0.2)',
              'rgba(255, 99, 132, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(255, 206, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)',
              'rgba(153, 102, 255, 0.2)',
              'rgba(255, 159, 64, 0.2)'             
              ],
              data: []
            }]
        },
        options: {
          legend: {
            display: true
          },
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            labels: [
                {
                    render: 'label',
                    position: 'outside'
                },
                {
                    render: 'percentage'
                }
            ],
          },
        },
        alphabet:['A','B','C','D','E','F','G','H','J','K','L','M','N',
                        'O','P','Q','R','S','T','U','V','W','X','Y','Z'
                    ],
      }
    },
    watch:{
        data:{
            handler(val){
            },
            deep:true
        }
    },
    created(){
        for(let i=1;i<this.data.length;i++){
            this.chartData.labels.push(`${this.alphabet[i-1]}:${this.data[i].checkCnt?this.data[i].checkCnt:0}`)
            if(this.data[i].checkCnt){
                this.chartData.datasets[0].data.push(100)
            }else{
                this.chartData.datasets[0].data.push(0)
            }
        }
    },
    mounted () {
      this.renderChart(this.chartData, this.options)
    }
  }
</script>