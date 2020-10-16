<script>
  import { Bar } from 'vue-chartjs'
  import labels from 'chartjs-plugin-datalabels';
  export default {
    extends: Bar,
    props:['data'],
    data() {
      return {
        chartData: {
          labels:[],
          datasets: [{
            label: 'Bar Chart',
            borderWidth: 1,
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
            borderColor: [
              'rgba(255,99,132,1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)',
              'rgba(153, 102, 255, 1)',
              'rgba(255, 159, 64, 1)',
              'rgba(255,99,132,1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)',
              'rgba(153, 102, 255, 1)',
              'rgba(255, 159, 64, 1)'
            ],
            pointBorderColor: '#2554FF',
            data: []
          }]
        },
        options: {
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true
              },
              gridLines: {
                display: true
              }
            }],
            xAxes: [{
              gridLines: {
                display: false
              }
            }]
          },
          legend: {
            display: true
          },
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            labels: [
                {
                    render: 'value',
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
                console.log('barChart:',val)
            },
            deep:true
        }
    },
    created(){
        console.log('barChart:',this.data)
        for(let i=1;i<this.data.length;i++){
            this.chartData.labels.push(this.alphabet[i-1])
            this.chartData.datasets[0].data.push(this.data[i].checkCnt)
        }
        // console.log(this.chartData.datasets)
    },
    mounted() {
      this.renderChart(this.chartData, this.options)
    }
  }
</script>