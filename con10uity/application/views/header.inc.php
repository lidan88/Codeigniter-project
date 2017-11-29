<? $user_info = $this->session->userdata("user_info");

//print_r($user_info);
//die;
 ?>
<div class="navbar navbar-fixed-top bs-docs-nav navbar-default" role="banner" style="">
  
    <div class="conjtainer">
      <!-- Menu button for smallar screens -->
      <div class="navbar-header">
		  <button class="navbar-toggle btn-navbar" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
			<span>Menu</span>
		  </button>
		  <!-- Site name for smallar screens -->
		  <a href="index.html" class="navbar-brand hidden-lg">AdminPanel</a>
		</div>
      
      

      <!-- Navigation starts -->
      <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation" style="background-color: white;">         
		<!--<ul class="nav navbar-nav"> 
			<li>
				<h1 style="margin: ; 0px;">Admin Panel</h1>
			</li>
		</ul>-->
		<? if(isset($user_info['logo'])){ ?>
		<img src="/user_data/<?=$user_info['logo']?>" height="40" style="margin-top: 5px;height: 40px;" alt="" />
		<? }else{ ?>
		<img src="/img/continuity-innovations-logo-final.jpg" alt="" height="40" style="margin-top: 5px;" />
		<? } ?>		
		<img style="margin-left: 10px; margin-top: 5px; padding-left: 10px; border-left: 1px solid #c7c7c7;" src="/img/con10uity.jpg" alt="" height="30" />
	
				
        <!-- Links -->
        <ul class="nav navbar-nav pull-right">
          <li class="dropdown pull-right">            
            <a data-toggle="dropdown" class="dropdown-toggle" href="#" style='color:#428bca;'>
              <i class="icon-user"></i> <?
              	
              	if(isset($user_info['username']))
              		echo 'Welcome '. $user_info['username'];
              	else {
              		echo "Welcome Admin";
              	}
              ?> <b class="caret"></b>              
            </a>
            
            <!-- Dropdown menu -->
            <ul class="dropdown-menu">
              <!--<li><a href="#"><i class="icon-user"></i> Profile</a></li>-->
              <li><a href="/admin/settings/"><i class="icon-cogs"></i> Settings</a></li>
              <li><a href="/auth/logout/"><i class="icon-off"></i> Logout</a></li>
            </ul>
          </li>
          
        </ul>
      </nav>

    </div>
  </div>