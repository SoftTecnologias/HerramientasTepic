<!-- Main Header -->
<header class="main-header">
    <!-- Header Navbar -->
    <a href="{{route('area.index')}}" class="logo">
        <div class="pull-left image logo-lg">
            {{Html::image('img/minilogo.png','Herramientas Tepic',['class'=>'img-responsive'])}}
        </div>
        <div class="pull-left image logo-mini">
            {{Html::image('img/minilogo50.png','HT',['class'=>'img-responsive'])}}
        </div>
    </a>
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Messages: style can be found in dropdown.less-->
                <li class="dropdown messages-menu">
                    <!-- Menu toggle button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <span class="label label-success">0</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">Tienes 0 mensajes</li>
                        <li>
                            <!-- inner menu: contains the messages -->
                            <ul class="menu">
                                <li><!-- start message -->
                                    <a href="#">
                                        <div class="pull-left">
                                            <!-- User Image -->
                                            {{Html::image('img/user.png' ,'User Image',['class'=>'img-circle'])}}
                                        </div>
                                        <!-- Message title and timestamp -->
                                        <h4>
                                            Support Team
                                            <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                        </h4>
                                        <!-- The message -->
                                        <p>Why not buy a new awesome theme?</p>
                                    </a>
                                </li><!-- end message -->
                            </ul><!-- /.menu -->
                        </li>
                        <li class="footer"><a href="#">See All Messages</a></li>
                    </ul>
                </li><!-- /.messages-menu -->

                <!-- Notifications Menu -->
                <li class="dropdown notifications-menu">
                    <!-- Menu toggle button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-warning">0</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have 0 notifications</li>
                        <li>
                            <!-- Inner Menu: contains the notifications -->
                            <ul class="menu">
                                <li><!-- start notification -->
                                    <a href="#">
                                        <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                    </a>
                                </li><!-- end notification -->
                            </ul>
                        </li>
                        <li class="footer"><a href="#">View all</a></li>
                    </ul>
                </li>
                <!-- Tasks Menu -->
                <li class="dropdown tasks-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-flag-o"></i>
                        <span class="label label-danger">0</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have 0 tasks</li>
                        <li>
                            <!-- Inner menu: contains the tasks -->
                            <ul class="menu">
                                <li><!-- Task item -->
                                    <a href="#">
                                        <!-- Task title and progress text -->
                                        <h3>
                                            Design some buttons
                                            <small class="pull-right">20%</small>
                                        </h3>
                                        <!-- The progress bar -->
                                        <div class="progress xs">
                                            <!-- Change the css width attribute to simulate progress -->
                                            <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                <span class="sr-only">20% Complete</span>
                                            </div>
                                        </div>
                                    </a>
                                </li><!-- end task item -->
                            </ul>
                        </li>
                        <li class="footer">
                            <a href="#">View all tasks</a>
                        </li>
                    </ul>
                </li>
                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <img src="{{asset('img/usuarios/'.$user_info['photo'])}}" alt="User Image" class="user-image"/>
                    <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs">{{$user_info['name']}}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <img src="{{asset('img/usuarios/'.$user_info['photo'])}}" alt="User Image" class="img-circle"/>
                        <p>
                            {{$user_info['name']}}
                            <small> Miembro desde {{$user_info['ingreso']}}</small>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="col-xs-4 text-center">
                                <a href="#">{{$user_info['permiso']}}</a>
                            </div>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left" >
                                <p class="btn btn-default btn-flat" id="btnPerfil">Perfil</p>
                            </div>
                            <div class="pull-right">
                                <a href="{{route('area.logout')}}" class="btn btn-default btn-flat">Salir</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>




<!-- Seccion del Modal -->
<div class = "modal" id="ModPer">
    <div class="container">
        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 toppad" id="datos">


                <div class="panel panel-info">
                    <div class="panel-heading">
                        <span>
                             <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                         class="fa fa-times"></i></button>
                            <h3 class="panel-title">{{$datos['name']}}</h3>
                        </span>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3 col-lg-3 " align="center"> <img alt="Foto Usuario" src="{{asset('img/usuarios/'.$datos['photo'])}}" class="img-circle img-responsive"> </div>



                            <!--<div class="col-xs-10 col-sm-10 hidden-md hidden-lg"> <br>
                              <dl>
                                <dt>DEPARTMENT:</dt>
                                <dd>Administrator</dd>
                                <dt>HIRE DATE</dt>
                                <dd>11/12/2013</dd>
                                <dt>DATE OF BIRTH</dt>
                                   <dd>11/12/2013</dd>
                                <dt>GENDER</dt>
                                <dd>Male</dd>
                              </dl>
                            </div>-->
                            <div class=" col-md-9 col-lg-9 ">
                                <table class="table table-user-information">
                                    <tbody>
                                    <tr>
                                        <td>Telefono:</td>
                                        <td>{{$usuario['phone']}}</td>
                                    </tr>
                                    <tr>
                                        <td>Fecha de Registro:</td>
                                        <td>{{$datos['ingreso']}}</td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td>{{$usuario['email']}}</td>

                                    </tbody>
                                </table>

                                <a href="#" class="btn btn-primary">Mis Ordenes</a>
                                <a href="#" class="btn btn-primary">Todas las Ordenes</a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">

                            <a href="{{route('area.perfil')}}" data-original-title="Editar mis Datos" data-toggle="tooltip" type="button" class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-edit"></i></a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('/js/admin/PerfilModal.js')}}" type="text/javascript"></script>
<style type="text/css">
    #datos {
        margin-top: 200px;
    }
</style>