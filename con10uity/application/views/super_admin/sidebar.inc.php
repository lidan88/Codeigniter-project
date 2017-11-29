<div class="sidebar">
        <div class="sidebar-dropdown"><a href="#">Navigation</a></div>

        <!--- Sidebar navigation -->
        <!-- If the main navigation has sub navigation, then add the class "has_sub" to "li" of main navigation. -->
        <ul id="nav">
		  <li class="sidebar-search">
				<div class="input-group custom-search-form">
					<input type="text" class="form-control" placeholder="Search...">
					<span class="input-group-btn">
						<i class="icon-search" style='color:#000;'></i>
					</span>
				</div>
				<!-- /input-group -->
          </li>	
          <!-- Main menu with font awesome icon -->
          <li><a href="/super_admin/"><!--i class="icon-home" style='color:#2094ca'></i!--> Dashboard</a>
            <!-- Sub menu markup 
            <ul>
              <li><a href="#">Submenu #1</a></li>
              <li><a href="#">Submenu #2</a></li>
              <li><a href="#">Submenu #3</a></li>
            </ul>-->
          </li>
          <li><a href="/super_admin/company/" <?=($this->router->class=='company' || $this->router->class=='clients')?'class="open"':''?>><!--i class="icon-home"></i!--> Companies</a>
          <li><a href="/super_admin/library/" <?=($this->router->class=='library')?'class="open"':''?>><!--i class="icon-home"></i!--> Library</a>
          <li><a href="/super_admin/role/" <?=($this->router->class=='role')?'class="open"':''?>><!--i class="icon-home"></i!--> Role</a>
          <li><a href="/super_admin/dropdown/" <?=($this->router->class=='dropdown')?'class="open"':''?>><!--i class="icon-home"></i!--> Drop Down</a>
          <li><a href="/super_admin/threats/" <?=($this->router->class=='threats')?'class="open"':''?>><!--i class="icon-home"></i!--> Threats</a>
          <li><a href="/super_admin/help_category/" <?=($this->router->class=='help_category')?'class="open"':''?>><!--i class="icon-home"></i!--> Help Categories</a>
          <li><a href="/super_admin/help/" <?=($this->router->class=='help')?'class="open"':''?>><!--i class="icon-home"></i!--> Help</a>
          <li><a href="/super_admin/gallery/" <?=($this->router->class=='gallery')?'class="open"':''?>><!--i class="icon-home"></i!--> Image Gallery</a>
          
          <!--
          <li><a href="/super_admin/clients/"  <?=($this->router->class=='clients')?'class="open"':''?>><i class="icon-home"></i> Clients</a>
          
          <li class="has_sub"><a href="#" class="open"><i><img src="/img/Library_16.png" alt="" /></i>  Users  <span class="pull-right"><i class="icon-chevron-right"></i></span></a>
            <ul>
              <li><a href="widgets1.html">Manage Users</a></li>
            </ul>
          </li>  
          <li><a href="charts.html"><i class="icon-bar-chart"></i> Risk Assessment</a></li> 
          <li><a href="tables.html"><i class="icon-table"></i> Business Impact Analysis</a></li>
         <li><a href="calendar.html"><i class="icon-calendar"></i> Dependencies</a></li>
          <li class="has_sub"><a href="#" class="open"><i class="icon-list-alt"></i> Administration  <span class="pull-right"><i class="icon-chevron-right"></i></span></a>
            <ul>
              <li><a href="widgets1.html">User Setup</a></li>
              <li><a href="widgets2.html">Role</a></li>
              <li><a href="widgets2.html">Import</a></li>
              <li><a href="widgets2.html">Drop Downs</a></li>
              <li><a href="widgets2.html">BIA Design</a></li>
              <li><a href="widgets2.html">Change Logo</a></li>
              <li><a href="widgets2.html">Plans</a></li>
              <li><a href="widgets2.html">Risk Assessment</a></li>
				<li><a href="widgets2.html">BIA</a></li>
				<li><a href="widgets2.html">Configure</a></li>
				<li><a href="widgets2.html">Email Templates</a></li>
				<li><a href="widgets2.html">User Notifications</a></li>
				<li><a href="widgets2.html">Message Log</a></li>
				<li><a href="widgets2.html">OCN Communication</a></li>
              
              
            </ul>
          </li>  -->        
          
          </ul>
    </div>