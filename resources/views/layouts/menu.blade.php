        <aside class="left-sidebar" data-sidebarbg="skin6">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar" data-sidebarbg="skin6">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('home') }}" aria-expanded="false">
                                <i data-feather="home" class="feather-icon"></i>
                                <span class="hide-menu">Home</span>
                            </a>
                        </li>

                        @if (Auth::user()->isAdmin())
                            <li class="sidebar-item"> <a class="sidebar-link" href="{{ route('admin.company') }}"
                                    aria-expanded="false"><i data-feather="home" class="feather-icon"></i><span
                                        class="hide-menu">Empresas </span></a>
                            </li>
                            <li class="sidebar-item"> <a class="sidebar-link" href="{{ route('admin.user') }}"
                                    aria-expanded="false"><i data-feather="user" class="feather-icon"></i><span
                                        class="hide-menu">Usuários </span></a>
                            </li>
                            <li class="sidebar-item"> <a class="sidebar-link"
                                    href="{{ route('admin.subscribers') }}" aria-expanded="false"><i data-feather="user"
                                        class="feather-icon"></i><span class="hide-menu">Incritos -
                                        Newsletter</span></a>
                            </li>
                            <li class="sidebar-item"> <a class="sidebar-link"
                                    href="{{ route('admin.demonstration_requests') }}" aria-expanded="false"><i
                                        data-feather="user" class="feather-icon"></i><span
                                        class="hide-menu">Solicitações de demonstração</span></a>
                            </li>
                            <li class="sidebar-item">
                                <div class="hide-menu sidebar-link d-block">
                                    <div class="">
                                        SpyLogin
                                    </div>
                                    <form method="POST" action="{{ route('admin.spylogin') }}">
                                        @csrf
                                        <div class="input-group">
                                            <input id="spylogin" type="email" name="email" class="form-control"
                                                autocomplete="email">
                                            <div class="input-group-append">
                                                <input type="submit" value="Go" class="btn btn-outline-secondary">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </li>
                        @else

                            <li class="sidebar-item"> <a class="sidebar-link" href="{{ route('calendar') }}"
                                    aria-expanded="false"><i data-feather="calendar" class="feather-icon"></i><span
                                        class="hide-menu">Agenda </span></a>
                            </li>

                            <li class="sidebar-item"> <a class="sidebar-link has-arrow" href="{{ route('task') }}"
                                    aria-expanded="false"><i data-feather="file-text" class="feather-icon"></i><span
                                        class="hide-menu">Atividades </span></a>

                                <ul aria-expanded="false" class="collapse first-level base-level-line">
                                    <li class="sidebar-item">
                                        <a href="{{ route('task') }}" class="sidebar-link"><i data-feather="list"
                                                class="feather-icon"></i><span class="hide-menu"> Lista
                                            </span></a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="{{ route('task.kanban') }}" class="sidebar-link"><i
                                                data-feather="file-text" class="feather-icon"></i><span
                                                class="hide-menu"> Board
                                            </span></a>
                                    </li>
                                </ul>
                            </li>
                            @if (Auth::user()->hasProfile('PROJECT_VIEW') || Auth::user()->hasProfile('PROJECT_EDIT'))
                            <li class="sidebar-item"> <a class="sidebar-link has-arrow"
                                    href="{{ route('products') }}" aria-expanded="false"><i
                                        data-feather="plus-square" class="feather-icon"></i><span
                                        class="hide-menu">Cadastros </span></a>

                                <ul aria-expanded="false" class="collapse first-level base-level-line">
                                    <li class="sidebar-item">
                                        <a href="{{ route('unidades') }}" class="sidebar-link"><i
                                                data-feather="clipboard" class="feather-icon"></i><span
                                                class="hide-menu"> Unidades
                                            </span></a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="{{ route('categories') }}" class="sidebar-link"><i
                                                data-feather="clipboard" class="feather-icon"></i><span
                                                class="hide-menu"> Categorias
                                            </span></a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="{{ route('products') }}" class="sidebar-link"><i
                                                data-feather="package" class="feather-icon"></i><span
                                                class="hide-menu"> Produtos
                                            </span></a>
                                    </li>
                                </ul>

                            </li>
                        @endif

                            @if (Auth::user()->hasProfile('PROJECT_VIEW') || Auth::user()->hasProfile('PROJECT_EDIT'))
                                <li class="sidebar-item"> <a class="sidebar-link has-arrow"
                                        href="{{ route('project') }}" aria-expanded="false"><i
                                            data-feather="file-text" class="feather-icon"></i><span
                                            class="hide-menu">Projetos </span></a>

                                    <ul aria-expanded="false" class="collapse first-level base-level-line">
                                        <li class="sidebar-item">
                                            <a href="{{ route('project') }}" class="sidebar-link"><i
                                                    data-feather="list" class="feather-icon"></i><span
                                                    class="hide-menu"> Lista
                                                </span></a>
                                        </li>
                                        @if (Auth::user()->hasProfile('PROJECT_EDIT'))
                                            <li class="sidebar-item">
                                                <a href="{{ route('project.category') }}" class="sidebar-link"><i
                                                        data-feather="file-text" class="feather-icon"></i><span
                                                        class="hide-menu"> Categorias
                                                    </span></a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a href="{{ route('project.status') }}" class="sidebar-link"><i
                                                        data-feather="file-text" class="feather-icon"></i><span
                                                        class="hide-menu"> Status
                                                    </span></a>
                                            </li>
                                            <li class="sidebar-item">
                                                <a href="{{ route('project.step') }}" class="sidebar-link"><i
                                                        data-feather="file-text" class="feather-icon"></i><span
                                                        class="hide-menu"> Etapas
                                                    </span></a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>
                            @endif


                            @if (Auth::user()->hasProfile('CLIENT_VIEW') || Auth::user()->hasProfile('CLIENT_EDIT'))
                                <li class="sidebar-item"> <a class="sidebar-link" href="{{ route('client') }}"
                                        aria-expanded="false"><i data-feather="user" class="feather-icon"></i><span
                                            class="hide-menu">Clientes </span></a>
                                </li>
                            @endif

                            @if (Auth::user()->hasProfile('PROVIDER_VIEW') || Auth::user()->hasProfile('PROVIDER_EDIT'))
                                <li class="sidebar-item"> <a class="sidebar-link" href="{{ route('provider') }}"
                                        aria-expanded="false"><i data-feather="user" class="feather-icon"></i><span
                                            class="hide-menu">Fornecedores </span></a>
                                </li>
                            @endif

                            @if (Auth::user()->hasProfile('FINANCE_VIEW') || Auth::user()->hasProfile('FINANCE_EDIT'))
                                <li class="sidebar-item"> <a class="sidebar-link has-arrow"
                                        href="{{ route('finance') }}" aria-expanded="false"><i
                                            data-feather="dollar-sign" class="feather-icon"></i><span
                                            class="hide-menu">Financeiro </span></a>

                                    <ul aria-expanded="false" class="collapse first-level base-level-line">
                                        <li class="sidebar-item">
                                            <a href="{{ route('finance') }}" class="sidebar-link"><i
                                                    data-feather="list" class="feather-icon"></i><span
                                                    class="hide-menu"> Contas
                                                </span></a>
                                        </li>
                                        @if (Auth::user()->hasProfile('PROJECT_EDIT'))
                                            <li class="sidebar-item">
                                                <a href="{{ route('finance.category') }}" class="sidebar-link"><i
                                                        data-feather="file-text" class="feather-icon"></i><span
                                                        class="hide-menu"> Categorias
                                                    </span></a>
                                            </li>
                                        @endif
                                    </ul>

                                </li>
                            @endif

                            @if (Auth::user()->hasProfile('REPORT_VIEW'))
                                <li class="sidebar-item"> <a class="sidebar-link" href="{{ route('report') }}"
                                        aria-expanded="false"><i data-feather="file-text"
                                            class="feather-icon"></i><span class="hide-menu">Relatórios
                                        </span></a>
                                </li>
                            @endif

                            @if (Auth::user()->hasProfile('ADMIN'))
                                <li class="list-divider"></li>
                                <li class="nav-small-cap"><span class="hide-menu">ADMIN</span></li>
                                <li class="sidebar-item"> <a class="sidebar-link" href="{{ route('company') }}"
                                        aria-expanded="false"><i data-feather="home" class="feather-icon"></i><span
                                            class="hide-menu">Empresa </span></a>
                                </li>

                                <li class="sidebar-item"> <a class="sidebar-link"
                                        href="{{ route('company.users') }}" aria-expanded="false"><i
                                            data-feather="user" class="feather-icon"></i><span
                                            class="hide-menu">Usuários </span></a>
                                </li>
                            @endif

                        @endif
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
