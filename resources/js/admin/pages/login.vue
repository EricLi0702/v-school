<template>
    <div>
        <!-- Top menu -->
		<nav class="navbar navbar-inverse bg-light _box_shadow" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-navbar-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand navbar-brand-login" href="#">Bootstrap Navbar Menu Template</a>
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="top-navbar-1">
					<ul class="nav navbar-nav navbar-right">
						<li><a href="#">Home</a></li>
						<li><a href="#">Features</a></li>
						<li><a href="#">Video</a></li>
						<li><a href="#">Clients</a></li>
						<li><a href="#">Plans</a></li>
						<li><a href="#">About</a></li>
						<li><a href="#">Faq</a></li>
					</ul>
				</div>
			</div>
		</nav>

        <div class="container">
            <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20 col-md-4">
                <div class="login_header">
                    <h1>Login to the dashboard</h1>
                </div>
                <div class="mb-2">
                    <Input type="text" v-model="data.email"  placeholder="Phone Number"  />
                </div>
                <div class="mb-2">
                    <Input type="password" v-model="data.password"  placeholder="******"  />
                </div>
                <div class="login_footer">
                    <Button type="primary" @click="login" :disabled="isLogging" :loading="isLogging">{{isLogging ? 'Loging...' : 'Login'}}</Button>
                </div>
            </div>
        </div>

        
    </div>
</template>


<script>
export default {
    data(){
        return {
            data : {
                phoneNumber : '', 
                password: ''
            }, 
            isLogging: false, 
        }
    }, 

    methods : {
        async login(){
            if(this.data.phoneNumber.trim()=='') return this.error('PhoneNumber is required')
            if(this.data.password.trim()=='') return this.error('Password is required')
            if(this.data.password.length < 6) return this.error('Incorrect login details')
            this.isLogging = true
            const res = await this.callApi('post', 'api/login', this.data)
            if(res.status===200){
                console.log(res)
                this.success(res.data.msg)
                window.location = '/'
            }else{
                if(res.status===401){
                    this.info(res.data.msg)
                }else if(res.status==422){
                    for(let i in res.data.errors){
                        this.error(res.data.errors[i][0])
                    }
                }
                else{
                    this.swr()
                }
            }
            this.isLogging = false
        }
    }
}
</script>






<style scoped>
    ._1adminOverveiw_table_recent {
        margin: 0 auto;
        margin-top: 220px;
    }
    .login_footer{
        text-align: center;
    }
    .login_header{
        text-align: center;
        margin-bottom: 25px;
    }
    
</style>