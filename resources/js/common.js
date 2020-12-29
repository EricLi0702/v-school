import {mapGetters,mapActions} from 'vuex';
export default{
    data(){
        return{
            alphabet:['A','B','C','D','E','F','G','H','J','K','L','M','N',
                        'O','P','Q','R','S','T','U','V','W','X','Y','Z'
                    ],
        }
    },
    methods:{
        async callApi(method,url,dataObj){
            try {
                return await axios({
                    method: method,
                    url: url,
                    data: dataObj
                })
            } catch (e) {
                return e.response
            }
        },
        info (nodesc,notitle="嘿") {
            this.$Message.warning({
                content: nodesc,
                duration: 3.5
            });
        },
        success (nodesc,notitle="大！") {
            this.$Message.success({
                content: nodesc,
                duration: 3.5
            });
        },
        warning (nodesc,notitle="") {
            this.$Message.warning({
                content: nodesc,
                duration: 3.5
            });
        },
        error (nodesc,notitle="嘿") {
            this.$Message.error({
                content: nodesc,
                duration: 3.5
            });
        },
        swr (nodesc="出问题了！ 请再试一次。",notitle="") {
            this.$Message.error({
                content: nodesc,
                duration: 3.5
            });
        },
        checkUserPermission(key){
            if(!this.userPermission) return true;
            let isPermitted = false;
            for(let schools of this.userPermission){
                for(let data of schools.data){
                    if(this.$route.path == '/'+data.name){
                        if(data[key]){
                            isPermitted = true;
                        }
                        break;
                    }
                }
            }
            return isPermitted;
        },
        TimeView(str){
            // let date =  new Date(str)
            let date = new Date(str);
            let mnth = ("0" + (date.getMonth() + 1)).slice(-2);
            let day = ("0" + date.getDate()).slice(-2);
            let hour = ("0" + date.getHours()).slice(-2);
            let min = ("0" + date.getMinutes()).slice(-2);
            let time = date.getFullYear() + '-' + mnth + '-' + day + ' ' + hour + ':' + min;
            return time
            // date = date.getTime()
            // return date
        },

        TimeViewHMS(str){
            // let date =  new Date(str)
            let date = new Date(str);
            let hour = ("0" + date.getHours()).slice(-2);
            let min = ("0" + date.getMinutes()).slice(-2);
            let sec = ("0" + date.getSeconds()).slice(-2);
            let time = hour + ':' + min + ':' + sec;
            return time
            // date = date.getTime()
            // return date
        },
        TimeViewYMD(str){
            let date = new Date(str);
            return date
        },

        TimeViewDHM(str){
            let date = new Date(str);
            let mnth = ("0" + (date.getMonth() + 1)).slice(-2);
            let day = ("0" + date.getDate()).slice(-2);
            let hour = ("0" + date.getHours()).slice(-2);
            let min = ("0" + date.getMinutes()).slice(-2);
            let time = mnth + '-' + day + ' ' + hour + ':' + min;
            return time
        }
    },
            
    computed:{
        ...mapGetters({
            'userPermission':'getUserPermission'
        }),

        isReadPermitted(){
            return this.checkUserPermission('read');
        },
        isWritePermitted(){
            return this.checkUserPermission('write');
        },
        isUpdatePermitted(){
            return this.checkUserPermission('update');
        },
        isDeletePermitted(){
            return this.checkUserPermission('delete');
        },
    }

}

$( document ).ready(function() {
    
    let navbar = document.getElementsByClassName("navbar");
    let colorWhiteTopItems = document.getElementsByClassName("open-draw-icon");
    let addPlusBtn1 = document.getElementsByClassName("add-post-content-icon")
    let addPlusBtn2 = document.getElementsByClassName("add-post-content-class-icon")
    
	let last_known_scroll_position = 0;
	window.addEventListener('scroll', function(e) {
        last_known_scroll_position = window.scrollY;
		if (last_known_scroll_position <= 164) {
			navbar[0].classList.remove('navbar-scroll');
            navbar[0].classList.add('bg-transparent');
            for(let i = 0; i < colorWhiteTopItems.length; i++){
				colorWhiteTopItems[i].classList.remove('color-white-scroll');
				colorWhiteTopItems[i].classList.add('color-white-top');
			}
		}
		else{
            navbar[0].classList.remove('bg-transparent');
			navbar[0].classList.add('navbar-scroll');
            for(let i = 0; i < colorWhiteTopItems.length; i++){
                colorWhiteTopItems[i].classList.add('color-white-scroll');
                colorWhiteTopItems[i].classList.remove('color-white-top');
            }
        }
        bodyScroll();
    });   

    function bodyScroll() {
        if(addPlusBtn1.length == 1){
            addPlusBtn1[0].style.visibility = "hidden";
            addPlusBtn1[0].style.opacity = 0;
            setTimeout(function() {
                addPlusBtn1[0].style.visibility = "visible";
                addPlusBtn1[0].style.opacity = 1;
            }, 1000);
        }
        if(addPlusBtn2.length == 1){
            addPlusBtn2[0].style.visibility = "hidden";
            addPlusBtn2[0].style.opacity = 0;
            setTimeout(function() {
                addPlusBtn2[0].style.visibility = "visible";
                addPlusBtn2[0].style.opacity = 1;
            }, 1000);
        }
    }

    $('.ripple').on('click', function(event) {
        event.preventDefault();
        var $btn = $(this),
          $div = $('<div/>'),
          btnOffset = $btn.offset(),
          xPos = event.pageX - btnOffset.left,
          yPos = event.pageY - btnOffset.top;
  
        $div.addClass('ripple-effect');
        $div
          .css({
            height: $btn.height(),
            width: $btn.height(),
            top: yPos - ($div.height() / 2),
            left: xPos - ($div.width() / 2),
            background: $btn.data("ripple-color") || "#fff"
          });
        $btn.append($div);
  
        window.setTimeout(function() {
          $div.remove();
        }, 2000);
      });
  
});