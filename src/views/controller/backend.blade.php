@extends('core::layout.backend')

@section('head')
    <title>Backend</title>
    @parent
@stop

@section('body')
    <body class="no-skin telenok-backend">
        <div class="navbar navbar-default navbar-fixed-top">
            <div class="navbar-inner">
				<a class="navbar-brand" href="telenok/"><small>{{{\Config::get('app.backend.brand')}}}</small></a>
				<ul class="nav ace-nav pull-right">

					@foreach($ListModuleMenuTop as $itemFirstLevel)

					@if (!$itemFirstLevel->get('parent'))

						@if ($itemFirstLevel->get('li'))
						{{$itemFirstLevel->get('li')}}
						@else
						<li>
						@endif

						{{$itemFirstLevel->get('content')}}

							<ul class="pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-closer" id="user_menu">
								@foreach($ListModuleMenuTop as $itemSecondLevel)

								@if ($itemFirstLevel->get('key') == $itemSecondLevel->get('parent'))

									@if ($itemSecondLevel->get('devider_before'))
										<li class="divider"></li>
									@endif

									@if ($itemFirstLevel->get('li'))
									{{$itemFirstLevel->get('li')}}
									@else
									<li>
									@endif

									{{$itemSecondLevel->get('content')}}

									@if ($itemSecondLevel->get('devider_after'))
										<li class="divider"></li>
									@endif

									</li>
								@endif

								@endforeach
							</ul>
						</li>

					@endif

					@endforeach

					<!--
					<li class="light-blue user-profile">

						<a data-toggle="dropdown" href="#" class="user-menu dropdown-toggle">
							<img class="nav-user-photo" src="packages/telenok/core/image/anonym.png" alt="Anonym">
							<span id="user_info">
								Welcome,John!
							</span>
							<i class="fa fa-caret-down"></i>
						</a>
						<ul class="pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-closer" id="user_menu">
							<li><a href="#"><i class="fa fa-cog"></i> Settings</a></li>
							<li><a href="#"><i class="fa fa-user"></i> Profile</a></li>
							<li class="divider"></li>
							<li><a href="#"><i class="fa fa-power-off"></i> Logout</a></li>
						</ul> 
					</li>

					-->
				</ul>
            </div>
        </div>

        <div class="main-container">
            <div id="sidebar" class="sidebar responsive">
                <div class="sidebar-shortcuts">
                    <div class="sidebar-shortcuts-large">
                        @foreach($listModuleGroup as $listModuleGroupItem)
                        <button title='{{{$listModuleGroupItem->getName()}}}' onclick='jQuery("ul.telenok-sidebar").hide(); jQuery("ul.telenok-sidebar-{{$listModuleGroupItem->getKey()}}").show();' class="btn btn-sm telenok-sidebar-{{ $listModuleGroupItem->getKey() }} {{{ $listModuleGroupItem->getButton() }}}"><i class="{{{ $listModuleGroupItem->getIcon() }}}"></i></button>
                        @endforeach
                    </div>
                    <div class="sidebar-shortcuts-mini">
                        @foreach($listModuleGroup as $listModuleGroupItem)
                        <span class="btn {{{ $listModuleGroupItem->getButton() }}}"></span>
                        @endforeach
                    </div>
                </div> 

                @foreach($listModuleGroup as $listModuleGroupItem) 
                <ul class="nav nav-list telenok-sidebar telenok-sidebar-{{$listModuleGroupItem->getKey()}}">
                    @foreach($listModule as $listModuleItem)
                    
                        @if ($listModuleGroupItem->getKey() == $listModuleItem->getGroup())
                        
                            @if ($listModuleItem->isParentAndSingle()) 
                            <li class="parent-single">
                                <a href="#" onclick='
                                    telenok.addModule( "{{ $listModuleItem->getKey() }}", "{{ $listModuleItem->getRouterActionParam() }}", function(moduleKey) {
                                                telenok.processModuleContent(moduleKey);
                                            }); 
                                            return false;'>
                                    <i class="menu-icon {{{ $listModuleItem->getIcon() }}}"></i>
                                    <span class="menu-text">{{{ $listModuleItem->getName() }}}</span>
                                </a>
                            </li>
                            @elseif (!$listModuleItem->getParent())
                            <li>
                                <a class="dropdown-toggle" href="#">
                                    <i class="menu-icon {{{ $listModuleItem->getIcon() }}}"></i>
                                    <span class="menu-text">{{{ $listModuleItem->getName() }}}</span>
                                    <b class="arrow fa fa-angle-down"></b>
                                </a>
                                <ul class="submenu"> 
									
                                    @foreach($listModule as $item)
                                    @if ($item->getParent() == $listModuleItem->getKey())
									
									<li class="">
										<a href="#" onclick='telenok.addModule("{{ $item->getKey() }}", "{{ $item->getRouterActionParam() }}", function(moduleKey) {
                                                telenok.processModuleContent(moduleKey);
                                            });
                                            return false;'>
											<i class="menu-icon fa fa-caret-right"></i>
											{{{ $item->getName() }}}
										</a>
										<b class="arrow"></b>
									</li>
                                    @endif
                                    @endforeach
                                </ul>
                            </li> 
                            @endif
                            
                        @endif
                    
                    @endforeach
                </ul>
                @endforeach
				<div id="sidebar-collapse" class="sidebar-toggle sidebar-collapse">
					<i data-icon2="ace-icon fa fa-angle-double-right" data-icon1="ace-icon fa fa-angle-double-left" class="ace-icon fa fa-angle-double-left"></i>
				</div>            
			</div>


            <div class="main-content clearfix">
                <div class="breadcrumbs">
                    <ul class="breadcrumb">
                        <li><i class="ace-icon fa fa-home home-icon"></i> <a href="telenok/">{{Lang::get('core::default.home')}}</a></li> 
                    </ul>

                    <div class="nav-search">
                        <form class="form-inline">
                            <span class="input-icon">
                                <input type="text" placeholder="Search ..." class="input-small search-query nav-search-input" autocomplete="off">
                                <i class="fa fa-search nav-search-icon"></i>
                            </span>
                        </form>
                    </div>
                </div>


                <div class="clearfix page-content">

					<div id="ace-settings-container" class="ace-settings-container">
						<div id="ace-settings-btn" class="btn btn-app btn-xs btn-warning ace-settings-btn">
							<i class="ace-icon fa fa-cog bigger-150"></i>
						</div>

						<div id="ace-settings-box" class="ace-settings-box clearfix">
							<div class="pull-left width-100">
								<div class="ace-settings-item">
									<div class="pull-left">
										<select class="hide" id="skin-colorpicker">
											<option value="#438EB9" data-skin="no-skin">#438EB9</option>
											<option value="#222A2D" data-skin="skin-1">#222A2D</option>
											<option value="#C6487E" data-skin="skin-2">#C6487E</option>
											<option value="#D0D0D0" data-skin="skin-3">#D0D0D0</option>
										</select>
									</div>
									<span>&nbsp; Choose Skin</span>
								</div>
								
								<div class="btn-group">
									<button class="btn dropdown-toggle btn-sm btn-warning" data-toggle="dropdown">
										User action
										<span class="ace-icon fa fa-caret-down icon-on-right"></span>
									</button>

									<ul class="dropdown-menu dropdown-default pull-right">
										<li>
											<a href="#" onclick="jQuery.post('{{ URL::route('cmf.clear.cache') }}'); return false;">Clear cache now</a>
										</li>
									</ul>
								</div>

							</div>
 
						</div>
					</div>
 
					
				</div>
            </div>
        </div>


        <div class="modal fade backend-notice">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header table-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4>{{{$controller->LL("notice.title")}}}</h4>
					</div>
					<div class="modal-body">
					</div>
					<div class="modal-footer">
						<button class="btn" data-dismiss="modal">{{{$controller->LL("btn.close")}}}</button> 
					</div>
				</div>
			</div>
        </div>
        
        
</body>
@stop