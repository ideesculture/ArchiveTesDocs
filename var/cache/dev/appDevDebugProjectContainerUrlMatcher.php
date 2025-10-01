<?php

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

/**
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class appDevDebugProjectContainerUrlMatcher extends Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher
{
    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    public function match($rawPathinfo)
    {
        $allow = [];
        $pathinfo = rawurldecode($rawPathinfo);
        $trimmedPathinfo = rtrim($pathinfo, '/');
        $context = $this->context;
        $request = $this->request ?: $this->createRequest($pathinfo);
        $requestMethod = $canonicalMethod = $context->getMethod();

        if ('HEAD' === $requestMethod) {
            $canonicalMethod = 'GET';
        }

        if (0 === strpos($pathinfo, '/_')) {
            // _wdt
            if (0 === strpos($pathinfo, '/_wdt') && preg_match('#^/_wdt/(?P<token>[^/]++)$#sD', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, ['_route' => '_wdt']), array (  '_controller' => 'web_profiler.controller.profiler:toolbarAction',));
            }

            if (0 === strpos($pathinfo, '/_profiler')) {
                // _profiler_home
                if ('/_profiler' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'web_profiler.controller.profiler:homeAction',  '_route' => '_profiler_home',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not__profiler_home;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', '_profiler_home'));
                    }

                    return $ret;
                }
                not__profiler_home:

                if (0 === strpos($pathinfo, '/_profiler/search')) {
                    // _profiler_search
                    if ('/_profiler/search' === $pathinfo) {
                        return array (  '_controller' => 'web_profiler.controller.profiler:searchAction',  '_route' => '_profiler_search',);
                    }

                    // _profiler_search_bar
                    if ('/_profiler/search_bar' === $pathinfo) {
                        return array (  '_controller' => 'web_profiler.controller.profiler:searchBarAction',  '_route' => '_profiler_search_bar',);
                    }

                }

                // _profiler_phpinfo
                if ('/_profiler/phpinfo' === $pathinfo) {
                    return array (  '_controller' => 'web_profiler.controller.profiler:phpinfoAction',  '_route' => '_profiler_phpinfo',);
                }

                // _profiler_search_results
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/search/results$#sD', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, ['_route' => '_profiler_search_results']), array (  '_controller' => 'web_profiler.controller.profiler:searchResultsAction',));
                }

                // _profiler_open_file
                if ('/_profiler/open' === $pathinfo) {
                    return array (  '_controller' => 'web_profiler.controller.profiler:openAction',  '_route' => '_profiler_open_file',);
                }

                // _profiler
                if (preg_match('#^/_profiler/(?P<token>[^/]++)$#sD', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, ['_route' => '_profiler']), array (  '_controller' => 'web_profiler.controller.profiler:panelAction',));
                }

                // _profiler_router
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/router$#sD', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, ['_route' => '_profiler_router']), array (  '_controller' => 'web_profiler.controller.router:panelAction',));
                }

                // _profiler_exception
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/exception$#sD', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, ['_route' => '_profiler_exception']), array (  '_controller' => 'web_profiler.controller.exception:showAction',));
                }

                // _profiler_exception_css
                if (preg_match('#^/_profiler/(?P<token>[^/]++)/exception\\.css$#sD', $pathinfo, $matches)) {
                    return $this->mergeDefaults(array_replace($matches, ['_route' => '_profiler_exception_css']), array (  '_controller' => 'web_profiler.controller.exception:cssAction',));
                }

            }

            // _twig_error_test
            if (0 === strpos($pathinfo, '/_error') && preg_match('#^/_error/(?P<code>\\d+)(?:\\.(?P<_format>[^/]++))?$#sD', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, ['_route' => '_twig_error_test']), array (  '_controller' => 'twig.controller.preview_error:previewErrorPageAction',  '_format' => 'html',));
            }

        }

        // bs_core_translation_homepage
        if (0 === strpos($pathinfo, '/translation/hello') && preg_match('#^/translation/hello/(?P<name>[^/]++)$#sD', $pathinfo, $matches)) {
            return $this->mergeDefaults(array_replace($matches, ['_route' => 'bs_core_translation_homepage']), array (  '_controller' => 'bs\\Core\\TranslationBundle\\Controller\\DefaultController::indexAction',));
        }

        if (0 === strpos($pathinfo, '/user')) {
            if (0 === strpos($pathinfo, '/user/admin')) {
                if (0 === strpos($pathinfo, '/user/admin/json')) {
                    // bs_core_admin_json_list_user
                    if ('/user/admin/json/list' === $trimmedPathinfo) {
                        $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\AdminJsonController::listAction',  '_route' => 'bs_core_admin_json_list_user',);
                        if ('/' === substr($pathinfo, -1)) {
                            // no-op
                        } elseif ('GET' !== $canonicalMethod) {
                            goto not_bs_core_admin_json_list_user;
                        } else {
                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_admin_json_list_user'));
                        }

                        return $ret;
                    }
                    not_bs_core_admin_json_list_user:

                    // bs_core_admin_json_delete_user
                    if ('/user/admin/json/delete' === $trimmedPathinfo) {
                        $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\AdminJsonController::deleteAction',  '_route' => 'bs_core_admin_json_delete_user',);
                        if ('/' === substr($pathinfo, -1)) {
                            // no-op
                        } elseif ('GET' !== $canonicalMethod) {
                            goto not_bs_core_admin_json_delete_user;
                        } else {
                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_admin_json_delete_user'));
                        }

                        return $ret;
                    }
                    not_bs_core_admin_json_delete_user:

                    if (0 === strpos($pathinfo, '/user/admin/json/ge')) {
                        if (0 === strpos($pathinfo, '/user/admin/json/getr')) {
                            // bs_core_admin_get_roles_list
                            if ('/user/admin/json/getroleslist' === $trimmedPathinfo) {
                                $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\AdminJsonController::getRolesListAction',  '_route' => 'bs_core_admin_get_roles_list',);
                                if ('/' === substr($pathinfo, -1)) {
                                    // no-op
                                } elseif ('GET' !== $canonicalMethod) {
                                    goto not_bs_core_admin_get_roles_list;
                                } else {
                                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_admin_get_roles_list'));
                                }

                                return $ret;
                            }
                            not_bs_core_admin_get_roles_list:

                            // bs_core_admin_json_get_rolerights_list
                            if ('/user/admin/json/getrolerightslist' === $trimmedPathinfo) {
                                $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\AdminJsonController::getRoleRightsListAction',  '_route' => 'bs_core_admin_json_get_rolerights_list',);
                                if ('/' === substr($pathinfo, -1)) {
                                    // no-op
                                } elseif ('GET' !== $canonicalMethod) {
                                    goto not_bs_core_admin_json_get_rolerights_list;
                                } else {
                                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_admin_json_get_rolerights_list'));
                                }

                                return $ret;
                            }
                            not_bs_core_admin_json_get_rolerights_list:

                            // bs_core_admin_json_get_rights_list
                            if ('/user/admin/json/getrightslist' === $trimmedPathinfo) {
                                $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\AdminJsonController::getRightsListAction',  '_route' => 'bs_core_admin_json_get_rights_list',);
                                if ('/' === substr($pathinfo, -1)) {
                                    // no-op
                                } elseif ('GET' !== $canonicalMethod) {
                                    goto not_bs_core_admin_json_get_rights_list;
                                } else {
                                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_admin_json_get_rights_list'));
                                }

                                return $ret;
                            }
                            not_bs_core_admin_json_get_rights_list:

                        }

                        // bs_core_admin_json_get_userrights_list
                        if ('/user/admin/json/getuserrightslist' === $trimmedPathinfo) {
                            $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\AdminJsonController::getUserRightsListAction',  '_route' => 'bs_core_admin_json_get_userrights_list',);
                            if ('/' === substr($pathinfo, -1)) {
                                // no-op
                            } elseif ('GET' !== $canonicalMethod) {
                                goto not_bs_core_admin_json_get_userrights_list;
                            } else {
                                return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_admin_json_get_userrights_list'));
                            }

                            return $ret;
                        }
                        not_bs_core_admin_json_get_userrights_list:

                        // bs_core_admin_json_generate_initials
                        if ('/user/admin/json/generate/initials' === $trimmedPathinfo) {
                            $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\AdminJsonController::generateinitialsAction',  '_route' => 'bs_core_admin_json_generate_initials',);
                            if ('/' === substr($pathinfo, -1)) {
                                // no-op
                            } elseif ('GET' !== $canonicalMethod) {
                                goto not_bs_core_admin_json_generate_initials;
                            } else {
                                return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_admin_json_generate_initials'));
                            }

                            return $ret;
                        }
                        not_bs_core_admin_json_generate_initials:

                    }

                    // bs_core_admin_json_set_userright
                    if ('/user/admin/json/setuserright' === $trimmedPathinfo) {
                        $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\AdminJsonController::setUserRightAction',  '_route' => 'bs_core_admin_json_set_userright',);
                        if ('/' === substr($pathinfo, -1)) {
                            // no-op
                        } elseif ('GET' !== $canonicalMethod) {
                            goto not_bs_core_admin_json_set_userright;
                        } else {
                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_admin_json_set_userright'));
                        }

                        return $ret;
                    }
                    not_bs_core_admin_json_set_userright:

                    // bs_core_admin_json_set_roleright
                    if ('/user/admin/json/setroleright' === $trimmedPathinfo) {
                        $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\AdminJsonController::setRoleRightAction',  '_route' => 'bs_core_admin_json_set_roleright',);
                        if ('/' === substr($pathinfo, -1)) {
                            // no-op
                        } elseif ('GET' !== $canonicalMethod) {
                            goto not_bs_core_admin_json_set_roleright;
                        } else {
                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_admin_json_set_roleright'));
                        }

                        return $ret;
                    }
                    not_bs_core_admin_json_set_roleright:

                    if (0 === strpos($pathinfo, '/user/admin/json/un')) {
                        // bs_core_admin_json_unset_userright
                        if ('/user/admin/json/unsetuserright' === $trimmedPathinfo) {
                            $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\AdminJsonController::unsetUserRightAction',  '_route' => 'bs_core_admin_json_unset_userright',);
                            if ('/' === substr($pathinfo, -1)) {
                                // no-op
                            } elseif ('GET' !== $canonicalMethod) {
                                goto not_bs_core_admin_json_unset_userright;
                            } else {
                                return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_admin_json_unset_userright'));
                            }

                            return $ret;
                        }
                        not_bs_core_admin_json_unset_userright:

                        // bs_core_admin_json_unset_roleright
                        if ('/user/admin/json/unsetroleright' === $trimmedPathinfo) {
                            $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\AdminJsonController::unsetRoleRightAction',  '_route' => 'bs_core_admin_json_unset_roleright',);
                            if ('/' === substr($pathinfo, -1)) {
                                // no-op
                            } elseif ('GET' !== $canonicalMethod) {
                                goto not_bs_core_admin_json_unset_roleright;
                            } else {
                                return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_admin_json_unset_roleright'));
                            }

                            return $ret;
                        }
                        not_bs_core_admin_json_unset_roleright:

                        // bs_core_admin_json_unlock_user
                        if ('/user/admin/json/unlockuser' === $trimmedPathinfo) {
                            $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\AdminJsonController::unlockUserAction',  '_route' => 'bs_core_admin_json_unlock_user',);
                            if ('/' === substr($pathinfo, -1)) {
                                // no-op
                            } elseif ('GET' !== $canonicalMethod) {
                                goto not_bs_core_admin_json_unlock_user;
                            } else {
                                return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_admin_json_unlock_user'));
                            }

                            return $ret;
                        }
                        not_bs_core_admin_json_unlock_user:

                    }

                    // bs_core_admin_json_updatefield_user
                    if ('/user/admin/json/updatefield' === $pathinfo) {
                        return array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\AdminJsonController::updatefieldAction',  '_route' => 'bs_core_admin_json_updatefield_user',);
                    }

                    // bs_core_admin_json_toggle_changepass
                    if ('/user/admin/json/togglechangepass' === $trimmedPathinfo) {
                        $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\AdminJsonController::toggleChangePassAction',  '_route' => 'bs_core_admin_json_toggle_changepass',);
                        if ('/' === substr($pathinfo, -1)) {
                            // no-op
                        } elseif ('GET' !== $canonicalMethod) {
                            goto not_bs_core_admin_json_toggle_changepass;
                        } else {
                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_admin_json_toggle_changepass'));
                        }

                        return $ret;
                    }
                    not_bs_core_admin_json_toggle_changepass:

                    // bs_core_admin_json_list_role
                    if ('/user/admin/json/roles/list' === $trimmedPathinfo) {
                        $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\AdminJsonController::rolesListAction',  '_route' => 'bs_core_admin_json_list_role',);
                        if ('/' === substr($pathinfo, -1)) {
                            // no-op
                        } elseif ('GET' !== $canonicalMethod) {
                            goto not_bs_core_admin_json_list_role;
                        } else {
                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_admin_json_list_role'));
                        }

                        return $ret;
                    }
                    not_bs_core_admin_json_list_role:

                    // bs_core_admin_json_delete_role
                    if ('/user/admin/json/roles/delete' === $trimmedPathinfo) {
                        $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\AdminJsonController::rolesDeleteAction',  '_route' => 'bs_core_admin_json_delete_role',);
                        if ('/' === substr($pathinfo, -1)) {
                            // no-op
                        } elseif ('GET' !== $canonicalMethod) {
                            goto not_bs_core_admin_json_delete_role;
                        } else {
                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_admin_json_delete_role'));
                        }

                        return $ret;
                    }
                    not_bs_core_admin_json_delete_role:

                    // bs_core_admin_json_verify_initials
                    if ('/user/admin/json/verify/initials' === $trimmedPathinfo) {
                        $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\AdminJsonController::verifyinitialsAction',  '_route' => 'bs_core_admin_json_verify_initials',);
                        if ('/' === substr($pathinfo, -1)) {
                            // no-op
                        } elseif ('GET' !== $canonicalMethod) {
                            goto not_bs_core_admin_json_verify_initials;
                        } else {
                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_admin_json_verify_initials'));
                        }

                        return $ret;
                    }
                    not_bs_core_admin_json_verify_initials:

                    // bs_core_admin_json_verify_login_unicity
                    if ('/user/admin/json/verify/loginunicity' === $trimmedPathinfo) {
                        $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\AdminJsonController::verifyloginunicityAction',  '_route' => 'bs_core_admin_json_verify_login_unicity',);
                        if ('/' === substr($pathinfo, -1)) {
                            // no-op
                        } elseif ('GET' !== $canonicalMethod) {
                            goto not_bs_core_admin_json_verify_login_unicity;
                        } else {
                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_admin_json_verify_login_unicity'));
                        }

                        return $ret;
                    }
                    not_bs_core_admin_json_verify_login_unicity:

                }

                // bs_core_users_admin_list
                if ('/user/admin/list' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\AdminController::listAction',  '_route' => 'bs_core_users_admin_list',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_core_users_admin_list;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_users_admin_list'));
                    }

                    return $ret;
                }
                not_bs_core_users_admin_list:

                // bs_core_users_admin_add
                if ('/user/admin/add' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\AdminController::addAction',  '_route' => 'bs_core_users_admin_add',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_core_users_admin_add;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_users_admin_add'));
                    }

                    return $ret;
                }
                not_bs_core_users_admin_add:

                // bs_core_users_admin_doadd
                if ('/user/admin/doadd' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\AdminController::doaddAction',  '_route' => 'bs_core_users_admin_doadd',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_core_users_admin_doadd;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_users_admin_doadd'));
                    }

                    return $ret;
                }
                not_bs_core_users_admin_doadd:

                // bs_core_users_admin_domodify
                if ('/user/admin/domodify' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\AdminController::domodifyAction',  '_route' => 'bs_core_users_admin_domodify',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_core_users_admin_domodify;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_users_admin_domodify'));
                    }

                    return $ret;
                }
                not_bs_core_users_admin_domodify:

                // bs_core_users_admin_modify
                if ('/user/admin/modify' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\AdminController::modifyAction',  '_route' => 'bs_core_users_admin_modify',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_core_users_admin_modify;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_users_admin_modify'));
                    }

                    return $ret;
                }
                not_bs_core_users_admin_modify:

                // bs_core_users_admin_finetune
                if ('/user/admin/finetune' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\AdminController::finetuneAction',  '_route' => 'bs_core_users_admin_finetune',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_core_users_admin_finetune;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_users_admin_finetune'));
                    }

                    return $ret;
                }
                not_bs_core_users_admin_finetune:

                if (0 === strpos($pathinfo, '/user/admin/roles')) {
                    // bs_core_roles_admin_list
                    if ('/user/admin/roles/list' === $trimmedPathinfo) {
                        $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\AdminController::rolesListAction',  '_route' => 'bs_core_roles_admin_list',);
                        if ('/' === substr($pathinfo, -1)) {
                            // no-op
                        } elseif ('GET' !== $canonicalMethod) {
                            goto not_bs_core_roles_admin_list;
                        } else {
                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_roles_admin_list'));
                        }

                        return $ret;
                    }
                    not_bs_core_roles_admin_list:

                    // bs_core_roles_admin_add
                    if ('/user/admin/roles/add' === $trimmedPathinfo) {
                        $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\AdminController::rolesAddAction',  '_route' => 'bs_core_roles_admin_add',);
                        if ('/' === substr($pathinfo, -1)) {
                            // no-op
                        } elseif ('GET' !== $canonicalMethod) {
                            goto not_bs_core_roles_admin_add;
                        } else {
                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_roles_admin_add'));
                        }

                        return $ret;
                    }
                    not_bs_core_roles_admin_add:

                    // bs_core_roles_admin_doadd
                    if ('/user/admin/roles/doadd' === $trimmedPathinfo) {
                        $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\AdminController::rolesDoAddAction',  '_route' => 'bs_core_roles_admin_doadd',);
                        if ('/' === substr($pathinfo, -1)) {
                            // no-op
                        } elseif ('GET' !== $canonicalMethod) {
                            goto not_bs_core_roles_admin_doadd;
                        } else {
                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_roles_admin_doadd'));
                        }

                        return $ret;
                    }
                    not_bs_core_roles_admin_doadd:

                    // bs_core_roles_admin_domodify
                    if ('/user/admin/roles/domodify' === $trimmedPathinfo) {
                        $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\AdminController::rolesDoModifyAction',  '_route' => 'bs_core_roles_admin_domodify',);
                        if ('/' === substr($pathinfo, -1)) {
                            // no-op
                        } elseif ('GET' !== $canonicalMethod) {
                            goto not_bs_core_roles_admin_domodify;
                        } else {
                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_roles_admin_domodify'));
                        }

                        return $ret;
                    }
                    not_bs_core_roles_admin_domodify:

                    // bs_core_roles_admin_modify
                    if ('/user/admin/roles/modify' === $trimmedPathinfo) {
                        $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\AdminController::rolesModifyAction',  '_route' => 'bs_core_roles_admin_modify',);
                        if ('/' === substr($pathinfo, -1)) {
                            // no-op
                        } elseif ('GET' !== $canonicalMethod) {
                            goto not_bs_core_roles_admin_modify;
                        } else {
                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_roles_admin_modify'));
                        }

                        return $ret;
                    }
                    not_bs_core_roles_admin_modify:

                    // bs_core_roles_admin_finetune
                    if ('/user/admin/roles/finetune' === $trimmedPathinfo) {
                        $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\AdminController::rolesFinetuneAction',  '_route' => 'bs_core_roles_admin_finetune',);
                        if ('/' === substr($pathinfo, -1)) {
                            // no-op
                        } elseif ('GET' !== $canonicalMethod) {
                            goto not_bs_core_roles_admin_finetune;
                        } else {
                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_roles_admin_finetune'));
                        }

                        return $ret;
                    }
                    not_bs_core_roles_admin_finetune:

                }

            }

            // bs_core_user_asf_update
            if ('/user/asf/json/update' === $trimmedPathinfo) {
                $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\UserAutoSaveFieldJsonController::updateAsfAction',  '_route' => 'bs_core_user_asf_update',);
                if ('/' === substr($pathinfo, -1)) {
                    // no-op
                } elseif ('GET' !== $canonicalMethod) {
                    goto not_bs_core_user_asf_update;
                } else {
                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_user_asf_update'));
                }

                return $ret;
            }
            not_bs_core_user_asf_update:

            if (0 === strpos($pathinfo, '/user/userspace/userfile')) {
                // bs_core_userspace_userfile_viewmainscreen
                if ('/user/userspace/userfile/viewmainscreen' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\UserSpaceUserFileController::viewMainScreenAction',  '_route' => 'bs_core_userspace_userfile_viewmainscreen',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_core_userspace_userfile_viewmainscreen;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_userspace_userfile_viewmainscreen'));
                    }

                    return $ret;
                }
                not_bs_core_userspace_userfile_viewmainscreen:

                // bs_core_userspace_userfile_deletefile
                if ('/user/userspace/userfile/deletefile' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\UserSpaceUserFileController::deleteFileAction',  '_route' => 'bs_core_userspace_userfile_deletefile',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_core_userspace_userfile_deletefile;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_userspace_userfile_deletefile'));
                    }

                    return $ret;
                }
                not_bs_core_userspace_userfile_deletefile:

                // bs_core_userspace_userfile_downloadfile
                if ('/user/userspace/userfile/downloadfile' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\UserSpaceUserFileController::downloadFileAction',  '_route' => 'bs_core_userspace_userfile_downloadfile',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_core_userspace_userfile_downloadfile;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_userspace_userfile_downloadfile'));
                    }

                    return $ret;
                }
                not_bs_core_userspace_userfile_downloadfile:

            }

            // bs_core_user_login
            if ('/user/login' === $trimmedPathinfo) {
                $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\UsersController::loginAction',  '_route' => 'bs_core_user_login',);
                if ('/' === substr($pathinfo, -1)) {
                    // no-op
                } elseif ('GET' !== $canonicalMethod) {
                    goto not_bs_core_user_login;
                } else {
                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_user_login'));
                }

                return $ret;
            }
            not_bs_core_user_login:

            // bs_core_user_logout
            if ('/user/logout' === $trimmedPathinfo) {
                $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\UsersController::logoutAction',  '_route' => 'bs_core_user_logout',);
                if ('/' === substr($pathinfo, -1)) {
                    // no-op
                } elseif ('GET' !== $canonicalMethod) {
                    goto not_bs_core_user_logout;
                } else {
                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_user_logout'));
                }

                return $ret;
            }
            not_bs_core_user_logout:

            // bs_core_user_checklogin
            if ('/user/checklogin' === $trimmedPathinfo) {
                $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\UsersController::checkloginAction',  '_route' => 'bs_core_user_checklogin',);
                if ('/' === substr($pathinfo, -1)) {
                    // no-op
                } elseif ('GET' !== $canonicalMethod) {
                    goto not_bs_core_user_checklogin;
                } else {
                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_user_checklogin'));
                }

                return $ret;
            }
            not_bs_core_user_checklogin:

            if (0 === strpos($pathinfo, '/user/changemdp')) {
                // bs_core_user_change_mdp_screen
                if ('/user/changemdpscreen' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\UsersController::changemdpscreenAction',  '_route' => 'bs_core_user_change_mdp_screen',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_core_user_change_mdp_screen;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_user_change_mdp_screen'));
                    }

                    return $ret;
                }
                not_bs_core_user_change_mdp_screen:

                // bs_core_user_change_mdp
                if ('/user/changemdp' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\Core\\UsersBundle\\Controller\\UsersController::changemdpAction',  '_route' => 'bs_core_user_change_mdp',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_core_user_change_mdp;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_core_user_change_mdp'));
                    }

                    return $ret;
                }
                not_bs_core_user_change_mdp:

            }

        }

        elseif (0 === strpos($pathinfo, '/backoffice')) {
            if (0 === strpos($pathinfo, '/backoffice/usersettings')) {
                // bs_idp_backoffice_ajax_usersettings_get
                if ('/backoffice/usersettings/get' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\IDP\\BackofficeBundle\\Controller\\UserSettingsJsonController::getUserSettingsAction',  '_route' => 'bs_idp_backoffice_ajax_usersettings_get',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_idp_backoffice_ajax_usersettings_get;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_backoffice_ajax_usersettings_get'));
                    }

                    return $ret;
                }
                not_bs_idp_backoffice_ajax_usersettings_get:

                // bs_idp_backoffice_usersettings_create_foruser
                if ('/backoffice/usersettings/createforuser' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\IDP\\BackofficeBundle\\Controller\\UserSettingsJsonController::createForUserAction',  '_route' => 'bs_idp_backoffice_usersettings_create_foruser',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_idp_backoffice_usersettings_create_foruser;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_backoffice_usersettings_create_foruser'));
                    }

                    return $ret;
                }
                not_bs_idp_backoffice_usersettings_create_foruser:

                // bs_idp_backoffice_usersettings_delete_foruser
                if ('/backoffice/usersettings/deleteforuser' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\IDP\\BackofficeBundle\\Controller\\UserSettingsJsonController::deleteForUserAction',  '_route' => 'bs_idp_backoffice_usersettings_delete_foruser',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_idp_backoffice_usersettings_delete_foruser;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_backoffice_usersettings_delete_foruser'));
                    }

                    return $ret;
                }
                not_bs_idp_backoffice_usersettings_delete_foruser:

                if (0 === strpos($pathinfo, '/backoffice/usersettings/modifycolumn')) {
                    // bs_idp_backoffice_usersettings_modify_column
                    if ('/backoffice/usersettings/modifycolumn' === $trimmedPathinfo) {
                        $ret = array (  '_controller' => 'bs\\IDP\\BackofficeBundle\\Controller\\UserSettingsJsonController::modifyColumnAction',  '_route' => 'bs_idp_backoffice_usersettings_modify_column',);
                        if ('/' === substr($pathinfo, -1)) {
                            // no-op
                        } elseif ('GET' !== $canonicalMethod) {
                            goto not_bs_idp_backoffice_usersettings_modify_column;
                        } else {
                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_backoffice_usersettings_modify_column'));
                        }

                        return $ret;
                    }
                    not_bs_idp_backoffice_usersettings_modify_column:

                    // bs_idp_backoffice_usersettings_modify_column_order
                    if ('/backoffice/usersettings/modifycolumnorder' === $trimmedPathinfo) {
                        $ret = array (  '_controller' => 'bs\\IDP\\BackofficeBundle\\Controller\\UserSettingsJsonController::modifyColumnOrderAction',  '_route' => 'bs_idp_backoffice_usersettings_modify_column_order',);
                        if ('/' === substr($pathinfo, -1)) {
                            // no-op
                        } elseif ('GET' !== $canonicalMethod) {
                            goto not_bs_idp_backoffice_usersettings_modify_column_order;
                        } else {
                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_backoffice_usersettings_modify_column_order'));
                        }

                        return $ret;
                    }
                    not_bs_idp_backoffice_usersettings_modify_column_order:

                }

                // bs_idp_backoffice_usersettings_modify_page
                if ('/backoffice/usersettings/modifypage' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\IDP\\BackofficeBundle\\Controller\\UserSettingsJsonController::modifyPageAction',  '_route' => 'bs_idp_backoffice_usersettings_modify_page',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_idp_backoffice_usersettings_modify_page;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_backoffice_usersettings_modify_page'));
                    }

                    return $ret;
                }
                not_bs_idp_backoffice_usersettings_modify_page:

            }

            elseif (0 === strpos($pathinfo, '/backoffice/json/get')) {
                // bs_idp_backoffice_get_services_list
                if ('/backoffice/json/getserviceslist' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\IDP\\BackofficeBundle\\Controller\\JsonController::getServicesListAction',  '_route' => 'bs_idp_backoffice_get_services_list',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_idp_backoffice_get_services_list;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_backoffice_get_services_list'));
                    }

                    return $ret;
                }
                not_bs_idp_backoffice_get_services_list:

                // bs_idp_backoffice_get_localizations_list
                if ('/backoffice/json/getlocalizationslist' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\IDP\\BackofficeBundle\\Controller\\JsonController::getLocalizationsListAction',  '_route' => 'bs_idp_backoffice_get_localizations_list',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_idp_backoffice_get_localizations_list;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_backoffice_get_localizations_list'));
                    }

                    return $ret;
                }
                not_bs_idp_backoffice_get_localizations_list:

                // bs_idp_backoffice_get_addresses_list
                if ('/backoffice/json/getaddresseslist' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\IDP\\BackofficeBundle\\Controller\\JsonController::getAddressesListAction',  '_route' => 'bs_idp_backoffice_get_addresses_list',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_idp_backoffice_get_addresses_list;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_backoffice_get_addresses_list'));
                    }

                    return $ret;
                }
                not_bs_idp_backoffice_get_addresses_list:

            }

            // bs_idp_backoffice_update_globalsettings_passwords
            if ('/backoffice/json/settings/update/globalsettings/passwords' === $pathinfo) {
                return array (  '_controller' => 'bs\\IDP\\BackofficeBundle\\Controller\\SettingsController::updateGlobalSettingsPasswordsAction',  '_route' => 'bs_idp_backoffice_update_globalsettings_passwords',);
            }

            if (0 === strpos($pathinfo, '/backoffice/settings')) {
                // bs_idp_backoffice_manage_visibility
                if ('/backoffice/settings/manage/visibility' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\IDP\\BackofficeBundle\\Controller\\SettingsController::manageVisibilityAction',  '_route' => 'bs_idp_backoffice_manage_visibility',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_idp_backoffice_manage_visibility;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_backoffice_manage_visibility'));
                    }

                    return $ret;
                }
                not_bs_idp_backoffice_manage_visibility:

                // bs_idp_backoffice_manage_globalsettings_passwords
                if ('/backoffice/settings/manage/globalsettings/passwords' === $pathinfo) {
                    return array (  '_controller' => 'bs\\IDP\\BackofficeBundle\\Controller\\SettingsController::manageGlobalSettingsPasswordsAction',  '_route' => 'bs_idp_backoffice_manage_globalsettings_passwords',);
                }

                // bs_idp_backoffice_set_settings
                if ('/backoffice/settings/set' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\IDP\\BackofficeBundle\\Controller\\SettingsController::setAction',  '_route' => 'bs_idp_backoffice_set_settings',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_idp_backoffice_set_settings;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_backoffice_set_settings'));
                    }

                    return $ret;
                }
                not_bs_idp_backoffice_set_settings:

                // bs_idp_backoffice_ajax_settings
                if ('/backoffice/settings/get' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\IDP\\BackofficeBundle\\Controller\\JsonController::getSettingsAction',  '_route' => 'bs_idp_backoffice_ajax_settings',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_idp_backoffice_ajax_settings;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_backoffice_ajax_settings'));
                    }

                    return $ret;
                }
                not_bs_idp_backoffice_ajax_settings:

                // bs_idp_backoffice_basket_settings
                if ('/backoffice/settings/baskets/get' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\IDP\\BackofficeBundle\\Controller\\JsonController::getBasketsSettingsAction',  '_route' => 'bs_idp_backoffice_basket_settings',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_idp_backoffice_basket_settings;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_backoffice_basket_settings'));
                    }

                    return $ret;
                }
                not_bs_idp_backoffice_basket_settings:

            }

        }

        // bs_idp_system_mail_viewed
        if ('/bssystem/bsmail/viewed' === $pathinfo) {
            return array (  '_controller' => 'bs\\IDP\\DashboardBundle\\Controller\\SystemBSMailController::viewedAction',  '_route' => 'bs_idp_system_mail_viewed',);
        }

        if (0 === strpos($pathinfo, '/archive')) {
            if (0 === strpos($pathinfo, '/archive/a')) {
                if (0 === strpos($pathinfo, '/archive/archivist')) {
                    if (0 === strpos($pathinfo, '/archive/archivist/json')) {
                        if (0 === strpos($pathinfo, '/archive/archivist/json/services')) {
                            // bs_idp_archivist_json_services_list
                            if ('/archive/archivist/json/services/list' === $trimmedPathinfo) {
                                $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBServicesJsonController::serviceslistAction',  '_route' => 'bs_idp_archivist_json_services_list',);
                                if ('/' === substr($pathinfo, -1)) {
                                    // no-op
                                } elseif ('GET' !== $canonicalMethod) {
                                    goto not_bs_idp_archivist_json_services_list;
                                } else {
                                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_services_list'));
                                }

                                return $ret;
                            }
                            not_bs_idp_archivist_json_services_list:

                            // bs_idp_archivist_json_services_delete
                            if ('/archive/archivist/json/services/delete' === $trimmedPathinfo) {
                                $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBServicesJsonController::servicesdeleteAction',  '_route' => 'bs_idp_archivist_json_services_delete',);
                                if ('/' === substr($pathinfo, -1)) {
                                    // no-op
                                } elseif ('GET' !== $canonicalMethod) {
                                    goto not_bs_idp_archivist_json_services_delete;
                                } else {
                                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_services_delete'));
                                }

                                return $ret;
                            }
                            not_bs_idp_archivist_json_services_delete:

                            // bs_idp_archivist_json_services_add
                            if ('/archive/archivist/json/services/add' === $trimmedPathinfo) {
                                $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBServicesJsonController::servicesaddAction',  '_route' => 'bs_idp_archivist_json_services_add',);
                                if ('/' === substr($pathinfo, -1)) {
                                    // no-op
                                } elseif ('GET' !== $canonicalMethod) {
                                    goto not_bs_idp_archivist_json_services_add;
                                } else {
                                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_services_add'));
                                }

                                return $ret;
                            }
                            not_bs_idp_archivist_json_services_add:

                            // bs_idp_archivist_json_services_modify
                            if ('/archive/archivist/json/services/modify' === $trimmedPathinfo) {
                                $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBServicesJsonController::servicesModifyAction',  '_route' => 'bs_idp_archivist_json_services_modify',);
                                if ('/' === substr($pathinfo, -1)) {
                                    // no-op
                                } elseif ('GET' !== $canonicalMethod) {
                                    goto not_bs_idp_archivist_json_services_modify;
                                } else {
                                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_services_modify'));
                                }

                                return $ret;
                            }
                            not_bs_idp_archivist_json_services_modify:

                        }

                        elseif (0 === strpos($pathinfo, '/archive/archivist/json/l')) {
                            if (0 === strpos($pathinfo, '/archive/archivist/json/legalentities')) {
                                // bs_idp_archivist_json_legalentities_list
                                if ('/archive/archivist/json/legalentities/list' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBLegalEntitiesJsonController::legalentitieslistAction',  '_route' => 'bs_idp_archivist_json_legalentities_list',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_json_legalentities_list;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_legalentities_list'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_json_legalentities_list:

                                if (0 === strpos($pathinfo, '/archive/archivist/json/legalentities/links')) {
                                    // bs_idp_archivist_json_legalentities_links_list
                                    if ('/archive/archivist/json/legalentities/links/list' === $trimmedPathinfo) {
                                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBLegalEntitiesJsonController::legalentitieslinkslistAction',  '_route' => 'bs_idp_archivist_json_legalentities_links_list',);
                                        if ('/' === substr($pathinfo, -1)) {
                                            // no-op
                                        } elseif ('GET' !== $canonicalMethod) {
                                            goto not_bs_idp_archivist_json_legalentities_links_list;
                                        } else {
                                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_legalentities_links_list'));
                                        }

                                        return $ret;
                                    }
                                    not_bs_idp_archivist_json_legalentities_links_list:

                                    // bs_idp_archivist_json_legalentities_links_set
                                    if ('/archive/archivist/json/legalentities/links/set' === $trimmedPathinfo) {
                                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBLegalEntitiesJsonController::legalentitieslinkssetAction',  '_route' => 'bs_idp_archivist_json_legalentities_links_set',);
                                        if ('/' === substr($pathinfo, -1)) {
                                            // no-op
                                        } elseif ('GET' !== $canonicalMethod) {
                                            goto not_bs_idp_archivist_json_legalentities_links_set;
                                        } else {
                                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_legalentities_links_set'));
                                        }

                                        return $ret;
                                    }
                                    not_bs_idp_archivist_json_legalentities_links_set:

                                    // bs_idp_archivist_json_legalentities_links_unset
                                    if ('/archive/archivist/json/legalentities/links/unset' === $trimmedPathinfo) {
                                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBLegalEntitiesJsonController::legalentitieslinksunsetAction',  '_route' => 'bs_idp_archivist_json_legalentities_links_unset',);
                                        if ('/' === substr($pathinfo, -1)) {
                                            // no-op
                                        } elseif ('GET' !== $canonicalMethod) {
                                            goto not_bs_idp_archivist_json_legalentities_links_unset;
                                        } else {
                                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_legalentities_links_unset'));
                                        }

                                        return $ret;
                                    }
                                    not_bs_idp_archivist_json_legalentities_links_unset:

                                }

                                // bs_idp_archivist_json_legalentities_delete
                                if ('/archive/archivist/json/legalentities/delete' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBLegalEntitiesJsonController::legalentitiesdeleteAction',  '_route' => 'bs_idp_archivist_json_legalentities_delete',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_json_legalentities_delete;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_legalentities_delete'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_json_legalentities_delete:

                                // bs_idp_archivist_json_legalentities_add
                                if ('/archive/archivist/json/legalentities/add' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBLegalEntitiesJsonController::legalentitiesaddAction',  '_route' => 'bs_idp_archivist_json_legalentities_add',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_json_legalentities_add;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_legalentities_add'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_json_legalentities_add:

                                // bs_idp_archivist_json_legalentities_modify
                                if ('/archive/archivist/json/legalentities/modify' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBLegalEntitiesJsonController::legalentitiesmodifyAction',  '_route' => 'bs_idp_archivist_json_legalentities_modify',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_json_legalentities_modify;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_legalentities_modify'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_json_legalentities_modify:

                            }

                            // bs_idp_archivist_json_loaddatas
                            if ('/archive/archivist/json/loaddatas' === $trimmedPathinfo) {
                                $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistJsonController::loaddatasAction',  '_route' => 'bs_idp_archivist_json_loaddatas',);
                                if ('/' === substr($pathinfo, -1)) {
                                    // no-op
                                } elseif ('GET' !== $canonicalMethod) {
                                    goto not_bs_idp_archivist_json_loaddatas;
                                } else {
                                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_loaddatas'));
                                }

                                return $ret;
                            }
                            not_bs_idp_archivist_json_loaddatas:

                            if (0 === strpos($pathinfo, '/archive/archivist/json/localizations')) {
                                // bs_idp_archivist_json_localizations_list
                                if ('/archive/archivist/json/localizations/list' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBLocalizationsJsonController::localizationslistAction',  '_route' => 'bs_idp_archivist_json_localizations_list',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_json_localizations_list;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_localizations_list'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_json_localizations_list:

                                // bs_idp_archivist_json_localizations_delete
                                if ('/archive/archivist/json/localizations/delete' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBLocalizationsJsonController::localizationsdeleteAction',  '_route' => 'bs_idp_archivist_json_localizations_delete',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_json_localizations_delete;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_localizations_delete'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_json_localizations_delete:

                                // bs_idp_archivist_json_localizations_add
                                if ('/archive/archivist/json/localizations/add' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBLocalizationsJsonController::localizationsaddAction',  '_route' => 'bs_idp_archivist_json_localizations_add',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_json_localizations_add;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_localizations_add'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_json_localizations_add:

                                // bs_idp_archivist_json_localizations_modify
                                if ('/archive/archivist/json/localizations/modify' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBLocalizationsJsonController::localizationsmodifyAction',  '_route' => 'bs_idp_archivist_json_localizations_modify',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_json_localizations_modify;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_localizations_modify'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_json_localizations_modify:

                                // bs_idp_archivist_json_localizations_change_logo
                                if ('/archive/archivist/json/localizations/change/logo' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBLocalizationsJsonController::localizationschangelogoAction',  '_route' => 'bs_idp_archivist_json_localizations_change_logo',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_json_localizations_change_logo;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_localizations_change_logo'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_json_localizations_change_logo:

                            }

                        }

                        elseif (0 === strpos($pathinfo, '/archive/archivist/json/budgetcodes')) {
                            // bs_idp_archivist_json_budgetcodes_list
                            if ('/archive/archivist/json/budgetcodes/list' === $trimmedPathinfo) {
                                $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBBudgetCodesJsonController::budgetcodeslistAction',  '_route' => 'bs_idp_archivist_json_budgetcodes_list',);
                                if ('/' === substr($pathinfo, -1)) {
                                    // no-op
                                } elseif ('GET' !== $canonicalMethod) {
                                    goto not_bs_idp_archivist_json_budgetcodes_list;
                                } else {
                                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_budgetcodes_list'));
                                }

                                return $ret;
                            }
                            not_bs_idp_archivist_json_budgetcodes_list:

                            if (0 === strpos($pathinfo, '/archive/archivist/json/budgetcodes/links')) {
                                // bs_idp_archivist_json_budgetcodes_links_list
                                if ('/archive/archivist/json/budgetcodes/links/list' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBBudgetCodesJsonController::budgetcodeslinkslistAction',  '_route' => 'bs_idp_archivist_json_budgetcodes_links_list',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_json_budgetcodes_links_list;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_budgetcodes_links_list'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_json_budgetcodes_links_list:

                                // bs_idp_archivist_json_budgetcodes_links_set
                                if ('/archive/archivist/json/budgetcodes/links/set' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBBudgetCodesJsonController::budgetcodeslinkssetAction',  '_route' => 'bs_idp_archivist_json_budgetcodes_links_set',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_json_budgetcodes_links_set;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_budgetcodes_links_set'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_json_budgetcodes_links_set:

                                // bs_idp_archivist_json_budgetcodes_links_unset
                                if ('/archive/archivist/json/budgetcodes/links/unset' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBBudgetCodesJsonController::budgetcodeslinksunsetAction',  '_route' => 'bs_idp_archivist_json_budgetcodes_links_unset',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_json_budgetcodes_links_unset;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_budgetcodes_links_unset'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_json_budgetcodes_links_unset:

                            }

                            // bs_idp_archivist_json_budgetcodes_delete
                            if ('/archive/archivist/json/budgetcodes/delete' === $trimmedPathinfo) {
                                $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBBudgetCodesJsonController::budgetcodesdeleteAction',  '_route' => 'bs_idp_archivist_json_budgetcodes_delete',);
                                if ('/' === substr($pathinfo, -1)) {
                                    // no-op
                                } elseif ('GET' !== $canonicalMethod) {
                                    goto not_bs_idp_archivist_json_budgetcodes_delete;
                                } else {
                                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_budgetcodes_delete'));
                                }

                                return $ret;
                            }
                            not_bs_idp_archivist_json_budgetcodes_delete:

                            // bs_idp_archivist_json_budgetcodes_add
                            if ('/archive/archivist/json/budgetcodes/add' === $trimmedPathinfo) {
                                $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBBudgetCodesJsonController::budgetcodesaddAction',  '_route' => 'bs_idp_archivist_json_budgetcodes_add',);
                                if ('/' === substr($pathinfo, -1)) {
                                    // no-op
                                } elseif ('GET' !== $canonicalMethod) {
                                    goto not_bs_idp_archivist_json_budgetcodes_add;
                                } else {
                                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_budgetcodes_add'));
                                }

                                return $ret;
                            }
                            not_bs_idp_archivist_json_budgetcodes_add:

                            // bs_idp_archivist_json_budgetcodes_modify
                            if ('/archive/archivist/json/budgetcodes/modify' === $trimmedPathinfo) {
                                $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBBudgetCodesJsonController::budgetcodesmodifyAction',  '_route' => 'bs_idp_archivist_json_budgetcodes_modify',);
                                if ('/' === substr($pathinfo, -1)) {
                                    // no-op
                                } elseif ('GET' !== $canonicalMethod) {
                                    goto not_bs_idp_archivist_json_budgetcodes_modify;
                                } else {
                                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_budgetcodes_modify'));
                                }

                                return $ret;
                            }
                            not_bs_idp_archivist_json_budgetcodes_modify:

                        }

                        elseif (0 === strpos($pathinfo, '/archive/archivist/json/d')) {
                            if (0 === strpos($pathinfo, '/archive/archivist/json/documentnatures')) {
                                // bs_idp_archivist_json_documentnatures_list
                                if ('/archive/archivist/json/documentnatures/list' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBDocumentNaturesJsonController::documentnatureslistAction',  '_route' => 'bs_idp_archivist_json_documentnatures_list',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_json_documentnatures_list;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_documentnatures_list'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_json_documentnatures_list:

                                if (0 === strpos($pathinfo, '/archive/archivist/json/documentnatures/links')) {
                                    // bs_idp_archivist_json_documentnatures_links_list
                                    if ('/archive/archivist/json/documentnatures/links/list' === $trimmedPathinfo) {
                                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBDocumentNaturesJsonController::documentnatureslinkslistAction',  '_route' => 'bs_idp_archivist_json_documentnatures_links_list',);
                                        if ('/' === substr($pathinfo, -1)) {
                                            // no-op
                                        } elseif ('GET' !== $canonicalMethod) {
                                            goto not_bs_idp_archivist_json_documentnatures_links_list;
                                        } else {
                                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_documentnatures_links_list'));
                                        }

                                        return $ret;
                                    }
                                    not_bs_idp_archivist_json_documentnatures_links_list:

                                    // bs_idp_archivist_json_documentnatures_links_set
                                    if ('/archive/archivist/json/documentnatures/links/set' === $trimmedPathinfo) {
                                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBDocumentNaturesJsonController::documentnatureslinkssetAction',  '_route' => 'bs_idp_archivist_json_documentnatures_links_set',);
                                        if ('/' === substr($pathinfo, -1)) {
                                            // no-op
                                        } elseif ('GET' !== $canonicalMethod) {
                                            goto not_bs_idp_archivist_json_documentnatures_links_set;
                                        } else {
                                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_documentnatures_links_set'));
                                        }

                                        return $ret;
                                    }
                                    not_bs_idp_archivist_json_documentnatures_links_set:

                                    // bs_idp_archivist_json_documentnatures_links_unset
                                    if ('/archive/archivist/json/documentnatures/links/unset' === $trimmedPathinfo) {
                                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBDocumentNaturesJsonController::documentnatureslinksunsetAction',  '_route' => 'bs_idp_archivist_json_documentnatures_links_unset',);
                                        if ('/' === substr($pathinfo, -1)) {
                                            // no-op
                                        } elseif ('GET' !== $canonicalMethod) {
                                            goto not_bs_idp_archivist_json_documentnatures_links_unset;
                                        } else {
                                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_documentnatures_links_unset'));
                                        }

                                        return $ret;
                                    }
                                    not_bs_idp_archivist_json_documentnatures_links_unset:

                                }

                                // bs_idp_archivist_json_documentnatures_delete
                                if ('/archive/archivist/json/documentnatures/delete' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBDocumentNaturesJsonController::documentnaturesdeleteAction',  '_route' => 'bs_idp_archivist_json_documentnatures_delete',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_json_documentnatures_delete;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_documentnatures_delete'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_json_documentnatures_delete:

                                // bs_idp_archivist_json_documentnatures_add
                                if ('/archive/archivist/json/documentnatures/add' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBDocumentNaturesJsonController::documentnaturesaddAction',  '_route' => 'bs_idp_archivist_json_documentnatures_add',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_json_documentnatures_add;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_documentnatures_add'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_json_documentnatures_add:

                                // bs_idp_archivist_json_documentnatures_modify
                                if ('/archive/archivist/json/documentnatures/modify' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBDocumentNaturesJsonController::documentnaturesmodifyAction',  '_route' => 'bs_idp_archivist_json_documentnatures_modify',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_json_documentnatures_modify;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_documentnatures_modify'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_json_documentnatures_modify:

                            }

                            elseif (0 === strpos($pathinfo, '/archive/archivist/json/documenttypes')) {
                                // bs_idp_archivist_json_documenttypes_list
                                if ('/archive/archivist/json/documenttypes/list' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBDocumentTypesJsonController::documenttypeslistAction',  '_route' => 'bs_idp_archivist_json_documenttypes_list',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_json_documenttypes_list;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_documenttypes_list'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_json_documenttypes_list:

                                if (0 === strpos($pathinfo, '/archive/archivist/json/documenttypes/links')) {
                                    // bs_idp_archivist_json_documenttypes_links_list
                                    if ('/archive/archivist/json/documenttypes/links/list' === $trimmedPathinfo) {
                                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBDocumentTypesJsonController::documenttypeslinkslistAction',  '_route' => 'bs_idp_archivist_json_documenttypes_links_list',);
                                        if ('/' === substr($pathinfo, -1)) {
                                            // no-op
                                        } elseif ('GET' !== $canonicalMethod) {
                                            goto not_bs_idp_archivist_json_documenttypes_links_list;
                                        } else {
                                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_documenttypes_links_list'));
                                        }

                                        return $ret;
                                    }
                                    not_bs_idp_archivist_json_documenttypes_links_list:

                                    // bs_idp_archivist_json_documenttypes_links_set
                                    if ('/archive/archivist/json/documenttypes/links/set' === $trimmedPathinfo) {
                                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBDocumentTypesJsonController::documenttypeslinkssetAction',  '_route' => 'bs_idp_archivist_json_documenttypes_links_set',);
                                        if ('/' === substr($pathinfo, -1)) {
                                            // no-op
                                        } elseif ('GET' !== $canonicalMethod) {
                                            goto not_bs_idp_archivist_json_documenttypes_links_set;
                                        } else {
                                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_documenttypes_links_set'));
                                        }

                                        return $ret;
                                    }
                                    not_bs_idp_archivist_json_documenttypes_links_set:

                                    // bs_idp_archivist_json_documenttypes_links_unset
                                    if ('/archive/archivist/json/documenttypes/links/unset' === $trimmedPathinfo) {
                                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBDocumentTypesJsonController::documenttypeslinksunsetAction',  '_route' => 'bs_idp_archivist_json_documenttypes_links_unset',);
                                        if ('/' === substr($pathinfo, -1)) {
                                            // no-op
                                        } elseif ('GET' !== $canonicalMethod) {
                                            goto not_bs_idp_archivist_json_documenttypes_links_unset;
                                        } else {
                                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_documenttypes_links_unset'));
                                        }

                                        return $ret;
                                    }
                                    not_bs_idp_archivist_json_documenttypes_links_unset:

                                }

                                // bs_idp_archivist_json_documenttypes_delete
                                if ('/archive/archivist/json/documenttypes/delete' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBDocumentTypesJsonController::documenttypesdeleteAction',  '_route' => 'bs_idp_archivist_json_documenttypes_delete',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_json_documenttypes_delete;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_documenttypes_delete'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_json_documenttypes_delete:

                                // bs_idp_archivist_json_documenttypes_add
                                if ('/archive/archivist/json/documenttypes/add' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBDocumentTypesJsonController::documenttypesaddAction',  '_route' => 'bs_idp_archivist_json_documenttypes_add',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_json_documenttypes_add;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_documenttypes_add'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_json_documenttypes_add:

                                // bs_idp_archivist_json_documenttypes_modify
                                if ('/archive/archivist/json/documenttypes/modify' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBDocumentTypesJsonController::documenttypesmodifyAction',  '_route' => 'bs_idp_archivist_json_documenttypes_modify',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_json_documenttypes_modify;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_documenttypes_modify'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_json_documenttypes_modify:

                            }

                            elseif (0 === strpos($pathinfo, '/archive/archivist/json/de')) {
                                if (0 === strpos($pathinfo, '/archive/archivist/json/descriptions1')) {
                                    // bs_idp_archivist_json_descriptions1_list
                                    if ('/archive/archivist/json/descriptions1/list' === $trimmedPathinfo) {
                                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBDescriptions1JsonController::descriptions1listAction',  '_route' => 'bs_idp_archivist_json_descriptions1_list',);
                                        if ('/' === substr($pathinfo, -1)) {
                                            // no-op
                                        } elseif ('GET' !== $canonicalMethod) {
                                            goto not_bs_idp_archivist_json_descriptions1_list;
                                        } else {
                                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_descriptions1_list'));
                                        }

                                        return $ret;
                                    }
                                    not_bs_idp_archivist_json_descriptions1_list:

                                    if (0 === strpos($pathinfo, '/archive/archivist/json/descriptions1/links')) {
                                        // bs_idp_archivist_json_descriptions1_links_list
                                        if ('/archive/archivist/json/descriptions1/links/list' === $trimmedPathinfo) {
                                            $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBDescriptions1JsonController::descriptions1linkslistAction',  '_route' => 'bs_idp_archivist_json_descriptions1_links_list',);
                                            if ('/' === substr($pathinfo, -1)) {
                                                // no-op
                                            } elseif ('GET' !== $canonicalMethod) {
                                                goto not_bs_idp_archivist_json_descriptions1_links_list;
                                            } else {
                                                return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_descriptions1_links_list'));
                                            }

                                            return $ret;
                                        }
                                        not_bs_idp_archivist_json_descriptions1_links_list:

                                        // bs_idp_archivist_json_descriptions1_links_set
                                        if ('/archive/archivist/json/descriptions1/links/set' === $trimmedPathinfo) {
                                            $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBDescriptions1JsonController::descriptions1linkssetAction',  '_route' => 'bs_idp_archivist_json_descriptions1_links_set',);
                                            if ('/' === substr($pathinfo, -1)) {
                                                // no-op
                                            } elseif ('GET' !== $canonicalMethod) {
                                                goto not_bs_idp_archivist_json_descriptions1_links_set;
                                            } else {
                                                return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_descriptions1_links_set'));
                                            }

                                            return $ret;
                                        }
                                        not_bs_idp_archivist_json_descriptions1_links_set:

                                        // bs_idp_archivist_json_descriptions1_links_unset
                                        if ('/archive/archivist/json/descriptions1/links/unset' === $trimmedPathinfo) {
                                            $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBDescriptions1JsonController::descriptions1linksunsetAction',  '_route' => 'bs_idp_archivist_json_descriptions1_links_unset',);
                                            if ('/' === substr($pathinfo, -1)) {
                                                // no-op
                                            } elseif ('GET' !== $canonicalMethod) {
                                                goto not_bs_idp_archivist_json_descriptions1_links_unset;
                                            } else {
                                                return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_descriptions1_links_unset'));
                                            }

                                            return $ret;
                                        }
                                        not_bs_idp_archivist_json_descriptions1_links_unset:

                                    }

                                    // bs_idp_archivist_json_descriptions1_delete
                                    if ('/archive/archivist/json/descriptions1/delete' === $trimmedPathinfo) {
                                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBDescriptions1JsonController::descriptions1deleteAction',  '_route' => 'bs_idp_archivist_json_descriptions1_delete',);
                                        if ('/' === substr($pathinfo, -1)) {
                                            // no-op
                                        } elseif ('GET' !== $canonicalMethod) {
                                            goto not_bs_idp_archivist_json_descriptions1_delete;
                                        } else {
                                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_descriptions1_delete'));
                                        }

                                        return $ret;
                                    }
                                    not_bs_idp_archivist_json_descriptions1_delete:

                                    // bs_idp_archivist_json_descriptions1_add
                                    if ('/archive/archivist/json/descriptions1/add' === $trimmedPathinfo) {
                                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBDescriptions1JsonController::descriptions1addAction',  '_route' => 'bs_idp_archivist_json_descriptions1_add',);
                                        if ('/' === substr($pathinfo, -1)) {
                                            // no-op
                                        } elseif ('GET' !== $canonicalMethod) {
                                            goto not_bs_idp_archivist_json_descriptions1_add;
                                        } else {
                                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_descriptions1_add'));
                                        }

                                        return $ret;
                                    }
                                    not_bs_idp_archivist_json_descriptions1_add:

                                    // bs_idp_archivist_json_descriptions1_modify
                                    if ('/archive/archivist/json/descriptions1/modify' === $trimmedPathinfo) {
                                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBDescriptions1JsonController::descriptions1modifyAction',  '_route' => 'bs_idp_archivist_json_descriptions1_modify',);
                                        if ('/' === substr($pathinfo, -1)) {
                                            // no-op
                                        } elseif ('GET' !== $canonicalMethod) {
                                            goto not_bs_idp_archivist_json_descriptions1_modify;
                                        } else {
                                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_descriptions1_modify'));
                                        }

                                        return $ret;
                                    }
                                    not_bs_idp_archivist_json_descriptions1_modify:

                                }

                                elseif (0 === strpos($pathinfo, '/archive/archivist/json/descriptions2')) {
                                    // bs_idp_archivist_json_descriptions2_list
                                    if ('/archive/archivist/json/descriptions2/list' === $trimmedPathinfo) {
                                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBDescriptions2JsonController::descriptions2listAction',  '_route' => 'bs_idp_archivist_json_descriptions2_list',);
                                        if ('/' === substr($pathinfo, -1)) {
                                            // no-op
                                        } elseif ('GET' !== $canonicalMethod) {
                                            goto not_bs_idp_archivist_json_descriptions2_list;
                                        } else {
                                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_descriptions2_list'));
                                        }

                                        return $ret;
                                    }
                                    not_bs_idp_archivist_json_descriptions2_list:

                                    if (0 === strpos($pathinfo, '/archive/archivist/json/descriptions2/links')) {
                                        // bs_idp_archivist_json_descriptions2_links_list
                                        if ('/archive/archivist/json/descriptions2/links/list' === $trimmedPathinfo) {
                                            $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBDescriptions2JsonController::descriptions2linkslistAction',  '_route' => 'bs_idp_archivist_json_descriptions2_links_list',);
                                            if ('/' === substr($pathinfo, -1)) {
                                                // no-op
                                            } elseif ('GET' !== $canonicalMethod) {
                                                goto not_bs_idp_archivist_json_descriptions2_links_list;
                                            } else {
                                                return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_descriptions2_links_list'));
                                            }

                                            return $ret;
                                        }
                                        not_bs_idp_archivist_json_descriptions2_links_list:

                                        // bs_idp_archivist_json_descriptions2_links_set
                                        if ('/archive/archivist/json/descriptions2/links/set' === $trimmedPathinfo) {
                                            $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBDescriptions2JsonController::descriptions2linkssetAction',  '_route' => 'bs_idp_archivist_json_descriptions2_links_set',);
                                            if ('/' === substr($pathinfo, -1)) {
                                                // no-op
                                            } elseif ('GET' !== $canonicalMethod) {
                                                goto not_bs_idp_archivist_json_descriptions2_links_set;
                                            } else {
                                                return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_descriptions2_links_set'));
                                            }

                                            return $ret;
                                        }
                                        not_bs_idp_archivist_json_descriptions2_links_set:

                                        // bs_idp_archivist_json_descriptions2_links_unset
                                        if ('/archive/archivist/json/descriptions2/links/unset' === $trimmedPathinfo) {
                                            $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBDescriptions2JsonController::descriptions2linksunsetAction',  '_route' => 'bs_idp_archivist_json_descriptions2_links_unset',);
                                            if ('/' === substr($pathinfo, -1)) {
                                                // no-op
                                            } elseif ('GET' !== $canonicalMethod) {
                                                goto not_bs_idp_archivist_json_descriptions2_links_unset;
                                            } else {
                                                return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_descriptions2_links_unset'));
                                            }

                                            return $ret;
                                        }
                                        not_bs_idp_archivist_json_descriptions2_links_unset:

                                    }

                                    // bs_idp_archivist_json_descriptions2_delete
                                    if ('/archive/archivist/json/descriptions2/delete' === $trimmedPathinfo) {
                                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBDescriptions2JsonController::descriptions2deleteAction',  '_route' => 'bs_idp_archivist_json_descriptions2_delete',);
                                        if ('/' === substr($pathinfo, -1)) {
                                            // no-op
                                        } elseif ('GET' !== $canonicalMethod) {
                                            goto not_bs_idp_archivist_json_descriptions2_delete;
                                        } else {
                                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_descriptions2_delete'));
                                        }

                                        return $ret;
                                    }
                                    not_bs_idp_archivist_json_descriptions2_delete:

                                    // bs_idp_archivist_json_descriptions2_add
                                    if ('/archive/archivist/json/descriptions2/add' === $trimmedPathinfo) {
                                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBDescriptions2JsonController::descriptions2addAction',  '_route' => 'bs_idp_archivist_json_descriptions2_add',);
                                        if ('/' === substr($pathinfo, -1)) {
                                            // no-op
                                        } elseif ('GET' !== $canonicalMethod) {
                                            goto not_bs_idp_archivist_json_descriptions2_add;
                                        } else {
                                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_descriptions2_add'));
                                        }

                                        return $ret;
                                    }
                                    not_bs_idp_archivist_json_descriptions2_add:

                                    // bs_idp_archivist_json_descriptions2_modify
                                    if ('/archive/archivist/json/descriptions2/modify' === $trimmedPathinfo) {
                                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBDescriptions2JsonController::descriptions2modifyAction',  '_route' => 'bs_idp_archivist_json_descriptions2_modify',);
                                        if ('/' === substr($pathinfo, -1)) {
                                            // no-op
                                        } elseif ('GET' !== $canonicalMethod) {
                                            goto not_bs_idp_archivist_json_descriptions2_modify;
                                        } else {
                                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_descriptions2_modify'));
                                        }

                                        return $ret;
                                    }
                                    not_bs_idp_archivist_json_descriptions2_modify:

                                }

                                elseif (0 === strpos($pathinfo, '/archive/archivist/json/deliveraddress')) {
                                    // bs_idp_archivist_json_deliveraddress_list
                                    if ('/archive/archivist/json/deliveraddress/list' === $trimmedPathinfo) {
                                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBDeliverAddressJsonController::deliveraddresslistAction',  '_route' => 'bs_idp_archivist_json_deliveraddress_list',);
                                        if ('/' === substr($pathinfo, -1)) {
                                            // no-op
                                        } elseif ('GET' !== $canonicalMethod) {
                                            goto not_bs_idp_archivist_json_deliveraddress_list;
                                        } else {
                                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_deliveraddress_list'));
                                        }

                                        return $ret;
                                    }
                                    not_bs_idp_archivist_json_deliveraddress_list:

                                    // bs_idp_archivist_json_deliveraddress_delete
                                    if ('/archive/archivist/json/deliveraddress/delete' === $trimmedPathinfo) {
                                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBDeliverAddressJsonController::deliveraddressdeleteAction',  '_route' => 'bs_idp_archivist_json_deliveraddress_delete',);
                                        if ('/' === substr($pathinfo, -1)) {
                                            // no-op
                                        } elseif ('GET' !== $canonicalMethod) {
                                            goto not_bs_idp_archivist_json_deliveraddress_delete;
                                        } else {
                                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_deliveraddress_delete'));
                                        }

                                        return $ret;
                                    }
                                    not_bs_idp_archivist_json_deliveraddress_delete:

                                    // bs_idp_archivist_json_deliveraddress_add
                                    if ('/archive/archivist/json/deliveraddress/add' === $trimmedPathinfo) {
                                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBDeliverAddressJsonController::deliveraddressaddAction',  '_route' => 'bs_idp_archivist_json_deliveraddress_add',);
                                        if ('/' === substr($pathinfo, -1)) {
                                            // no-op
                                        } elseif ('GET' !== $canonicalMethod) {
                                            goto not_bs_idp_archivist_json_deliveraddress_add;
                                        } else {
                                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_deliveraddress_add'));
                                        }

                                        return $ret;
                                    }
                                    not_bs_idp_archivist_json_deliveraddress_add:

                                    // bs_idp_archivist_json_deliveraddress_modify
                                    if ('/archive/archivist/json/deliveraddress/modify' === $trimmedPathinfo) {
                                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBDeliverAddressJsonController::deliveraddressModifyAction',  '_route' => 'bs_idp_archivist_json_deliveraddress_modify',);
                                        if ('/' === substr($pathinfo, -1)) {
                                            // no-op
                                        } elseif ('GET' !== $canonicalMethod) {
                                            goto not_bs_idp_archivist_json_deliveraddress_modify;
                                        } else {
                                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_deliveraddress_modify'));
                                        }

                                        return $ret;
                                    }
                                    not_bs_idp_archivist_json_deliveraddress_modify:

                                }

                            }

                        }

                        elseif (0 === strpos($pathinfo, '/archive/archivist/json/providers')) {
                            // bs_idp_archivist_json_providers_list
                            if ('/archive/archivist/json/providers/list' === $trimmedPathinfo) {
                                $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBProvidersJsonController::providerslistAction',  '_route' => 'bs_idp_archivist_json_providers_list',);
                                if ('/' === substr($pathinfo, -1)) {
                                    // no-op
                                } elseif ('GET' !== $canonicalMethod) {
                                    goto not_bs_idp_archivist_json_providers_list;
                                } else {
                                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_providers_list'));
                                }

                                return $ret;
                            }
                            not_bs_idp_archivist_json_providers_list:

                            if (0 === strpos($pathinfo, '/archive/archivist/json/providers/links')) {
                                // bs_idp_archivist_json_providers_links_list
                                if ('/archive/archivist/json/providers/links/list' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBProvidersJsonController::providerslinkslistAction',  '_route' => 'bs_idp_archivist_json_providers_links_list',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_json_providers_links_list;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_providers_links_list'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_json_providers_links_list:

                                // bs_idp_archivist_json_providers_links_set
                                if ('/archive/archivist/json/providers/links/set' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBProvidersJsonController::providerslinkssetAction',  '_route' => 'bs_idp_archivist_json_providers_links_set',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_json_providers_links_set;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_providers_links_set'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_json_providers_links_set:

                                // bs_idp_archivist_json_providers_links_unset
                                if ('/archive/archivist/json/providers/links/unset' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBProvidersJsonController::providerslinksunsetAction',  '_route' => 'bs_idp_archivist_json_providers_links_unset',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_json_providers_links_unset;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_providers_links_unset'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_json_providers_links_unset:

                            }

                            // bs_idp_archivist_json_providers_delete
                            if ('/archive/archivist/json/providers/delete' === $trimmedPathinfo) {
                                $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBProvidersJsonController::providersdeleteAction',  '_route' => 'bs_idp_archivist_json_providers_delete',);
                                if ('/' === substr($pathinfo, -1)) {
                                    // no-op
                                } elseif ('GET' !== $canonicalMethod) {
                                    goto not_bs_idp_archivist_json_providers_delete;
                                } else {
                                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_providers_delete'));
                                }

                                return $ret;
                            }
                            not_bs_idp_archivist_json_providers_delete:

                            // bs_idp_archivist_json_providers_add
                            if ('/archive/archivist/json/providers/add' === $trimmedPathinfo) {
                                $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBProvidersJsonController::providersaddAction',  '_route' => 'bs_idp_archivist_json_providers_add',);
                                if ('/' === substr($pathinfo, -1)) {
                                    // no-op
                                } elseif ('GET' !== $canonicalMethod) {
                                    goto not_bs_idp_archivist_json_providers_add;
                                } else {
                                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_providers_add'));
                                }

                                return $ret;
                            }
                            not_bs_idp_archivist_json_providers_add:

                            // bs_idp_archivist_json_providers_modify
                            if ('/archive/archivist/json/providers/modify' === $trimmedPathinfo) {
                                $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ManageDBProvidersJsonController::providersmodifyAction',  '_route' => 'bs_idp_archivist_json_providers_modify',);
                                if ('/' === substr($pathinfo, -1)) {
                                    // no-op
                                } elseif ('GET' !== $canonicalMethod) {
                                    goto not_bs_idp_archivist_json_providers_modify;
                                } else {
                                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_providers_modify'));
                                }

                                return $ret;
                            }
                            not_bs_idp_archivist_json_providers_modify:

                        }

                        elseif (0 === strpos($pathinfo, '/archive/archivist/json/providerconnector')) {
                            // bs_idp_archivist_json_providerconnectorbackup_get
                            if ('/archive/archivist/json/providerconnectorbackup/get' === $trimmedPathinfo) {
                                $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistJsonController::getProviderConnectorBackupAction',  '_route' => 'bs_idp_archivist_json_providerconnectorbackup_get',);
                                if ('/' === substr($pathinfo, -1)) {
                                    // no-op
                                } elseif ('GET' !== $canonicalMethod) {
                                    goto not_bs_idp_archivist_json_providerconnectorbackup_get;
                                } else {
                                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_providerconnectorbackup_get'));
                                }

                                return $ret;
                            }
                            not_bs_idp_archivist_json_providerconnectorbackup_get:

                            // bs_idp_archivist_json_providerconnectorbackup_set
                            if ('/archive/archivist/json/providerconnectorbackup/set' === $trimmedPathinfo) {
                                $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistJsonController::setProviderConnectorBackupAction',  '_route' => 'bs_idp_archivist_json_providerconnectorbackup_set',);
                                if ('/' === substr($pathinfo, -1)) {
                                    // no-op
                                } elseif ('GET' !== $canonicalMethod) {
                                    goto not_bs_idp_archivist_json_providerconnectorbackup_set;
                                } else {
                                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_providerconnectorbackup_set'));
                                }

                                return $ret;
                            }
                            not_bs_idp_archivist_json_providerconnectorbackup_set:

                            if (0 === strpos($pathinfo, '/archive/archivist/json/providerconnector_')) {
                                // bs_idp_archivist_json_providerconnector_optimisationstatus
                                if ('/archive/archivist/json/providerconnector_optimisationstatus' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistJsonController::getOptimisationStatusAction',  '_route' => 'bs_idp_archivist_json_providerconnector_optimisationstatus',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_json_providerconnector_optimisationstatus;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_providerconnector_optimisationstatus'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_json_providerconnector_optimisationstatus:

                                // bs_idp_archivist_provider_connector_lock_basket
                                if ('/archive/archivist/json/providerconnector_lockbasket' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistJsonController::lockBasketAction',  '_route' => 'bs_idp_archivist_provider_connector_lock_basket',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_provider_connector_lock_basket;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_provider_connector_lock_basket'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_provider_connector_lock_basket:

                                // bs_idp_archivist_provider_connector_unlock_basket
                                if ('/archive/archivist/json/providerconnector_unlockbasket' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistJsonController::unlockBasketAction',  '_route' => 'bs_idp_archivist_provider_connector_unlock_basket',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_provider_connector_unlock_basket;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_provider_connector_unlock_basket'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_provider_connector_unlock_basket:

                                // bs_idp_archivist_provider_connector_unmanage_optimization_choices
                                if ('/archive/archivist/json/providerconnector_unmanageoptimizationchoices' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistJsonController::unmanageOptimizationChoicesAction',  '_route' => 'bs_idp_archivist_provider_connector_unmanage_optimization_choices',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_provider_connector_unmanage_optimization_choices;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_provider_connector_unmanage_optimization_choices'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_provider_connector_unmanage_optimization_choices:

                                // bs_idp_archivist_provider_connector_manage_optimization_choices
                                if ('/archive/archivist/json/providerconnector_manageoptimizationchoices' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistJsonController::manageOptimizationChoicesAction',  '_route' => 'bs_idp_archivist_provider_connector_manage_optimization_choices',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_provider_connector_manage_optimization_choices;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_provider_connector_manage_optimization_choices'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_provider_connector_manage_optimization_choices:

                            }

                            // bs_idp_archivist_provider_connector_optimisation
                            if ('/archive/archivist/json/providerconnectoroptimisation' === $trimmedPathinfo) {
                                $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistJsonController::providerConnectorOptimisationAction',  '_route' => 'bs_idp_archivist_provider_connector_optimisation',);
                                if ('/' === substr($pathinfo, -1)) {
                                    // no-op
                                } elseif ('GET' !== $canonicalMethod) {
                                    goto not_bs_idp_archivist_provider_connector_optimisation;
                                } else {
                                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_provider_connector_optimisation'));
                                }

                                return $ret;
                            }
                            not_bs_idp_archivist_provider_connector_optimisation:

                            // bs_idp_archivist_provider_connector_ungray
                            if ('/archive/archivist/json/providerconnectorungray' === $trimmedPathinfo) {
                                $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistJsonController::providerConnectorUngrayAction',  '_route' => 'bs_idp_archivist_provider_connector_ungray',);
                                if ('/' === substr($pathinfo, -1)) {
                                    // no-op
                                } elseif ('GET' !== $canonicalMethod) {
                                    goto not_bs_idp_archivist_provider_connector_ungray;
                                } else {
                                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_provider_connector_ungray'));
                                }

                                return $ret;
                            }
                            not_bs_idp_archivist_provider_connector_ungray:

                        }

                        // bs_idp_archivist_json_action
                        if ('/archive/archivist/json/action' === $trimmedPathinfo) {
                            $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistJsonController::actionAction',  '_route' => 'bs_idp_archivist_json_action',);
                            if ('/' === substr($pathinfo, -1)) {
                                // no-op
                            } elseif ('GET' !== $canonicalMethod) {
                                goto not_bs_idp_archivist_json_action;
                            } else {
                                return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_action'));
                            }

                            return $ret;
                        }
                        not_bs_idp_archivist_json_action:

                        // bs_idp_archivist_json_cancel
                        if ('/archive/archivist/json/cancel' === $trimmedPathinfo) {
                            $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistJsonController::cancelAction',  '_route' => 'bs_idp_archivist_json_cancel',);
                            if ('/' === substr($pathinfo, -1)) {
                                // no-op
                            } elseif ('GET' !== $canonicalMethod) {
                                goto not_bs_idp_archivist_json_cancel;
                            } else {
                                return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_cancel'));
                            }

                            return $ret;
                        }
                        not_bs_idp_archivist_json_cancel:

                        // bs_idp_archivist_json_is_basket_verified
                        if ('/archive/archivist/json/containerbox_verification' === $trimmedPathinfo) {
                            $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistJsonController::isBasketVerifiedAction',  '_route' => 'bs_idp_archivist_json_is_basket_verified',);
                            if ('/' === substr($pathinfo, -1)) {
                                // no-op
                            } elseif ('GET' !== $canonicalMethod) {
                                goto not_bs_idp_archivist_json_is_basket_verified;
                            } else {
                                return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_is_basket_verified'));
                            }

                            return $ret;
                        }
                        not_bs_idp_archivist_json_is_basket_verified:

                        if (0 === strpos($pathinfo, '/archive/archivist/json/update')) {
                            // bs_idp_archivist_json_update_container
                            if ('/archive/archivist/json/updatecontainer' === $trimmedPathinfo) {
                                $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistJsonController::updatecontainerAction',  '_route' => 'bs_idp_archivist_json_update_container',);
                                if ('/' === substr($pathinfo, -1)) {
                                    // no-op
                                } elseif ('GET' !== $canonicalMethod) {
                                    goto not_bs_idp_archivist_json_update_container;
                                } else {
                                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_update_container'));
                                }

                                return $ret;
                            }
                            not_bs_idp_archivist_json_update_container:

                            // bs_idp_archivist_json_update_localization
                            if ('/archive/archivist/json/updatelocalization' === $trimmedPathinfo) {
                                $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistJsonController::updatelocalizationAction',  '_route' => 'bs_idp_archivist_json_update_localization',);
                                if ('/' === substr($pathinfo, -1)) {
                                    // no-op
                                } elseif ('GET' !== $canonicalMethod) {
                                    goto not_bs_idp_archivist_json_update_localization;
                                } else {
                                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_update_localization'));
                                }

                                return $ret;
                            }
                            not_bs_idp_archivist_json_update_localization:

                            // bs_idp_archivist_json_update_box
                            if ('/archive/archivist/json/updatebox' === $trimmedPathinfo) {
                                $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistJsonController::updateboxAction',  '_route' => 'bs_idp_archivist_json_update_box',);
                                if ('/' === substr($pathinfo, -1)) {
                                    // no-op
                                } elseif ('GET' !== $canonicalMethod) {
                                    goto not_bs_idp_archivist_json_update_box;
                                } else {
                                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_update_box'));
                                }

                                return $ret;
                            }
                            not_bs_idp_archivist_json_update_box:

                            // bs_idp_archivist_json_update_unlimited
                            if ('/archive/archivist/json/updateunlimited' === $trimmedPathinfo) {
                                $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistJsonController::updateunlimitedAction',  '_route' => 'bs_idp_archivist_json_update_unlimited',);
                                if ('/' === substr($pathinfo, -1)) {
                                    // no-op
                                } elseif ('GET' !== $canonicalMethod) {
                                    goto not_bs_idp_archivist_json_update_unlimited;
                                } else {
                                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_update_unlimited'));
                                }

                                return $ret;
                            }
                            not_bs_idp_archivist_json_update_unlimited:

                        }

                        // bs_idp_archivist_json_undo_manage_optimizationask
                        if ('/archive/archivist/json/undo_manage_optimizationask' === $trimmedPathinfo) {
                            $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistJsonController::undoManageOptimizationAskAction',  '_route' => 'bs_idp_archivist_json_undo_manage_optimizationask',);
                            if ('/' === substr($pathinfo, -1)) {
                                // no-op
                            } elseif ('GET' !== $canonicalMethod) {
                                goto not_bs_idp_archivist_json_undo_manage_optimizationask;
                            } else {
                                return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_undo_manage_optimizationask'));
                            }

                            return $ret;
                        }
                        not_bs_idp_archivist_json_undo_manage_optimizationask:

                        // bs_idp_archivist_json_allowed_providers
                        if ('/archive/archivist/json/get_allowed_providers' === $trimmedPathinfo) {
                            $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistJsonController::getAllowedProvidersAction',  '_route' => 'bs_idp_archivist_json_allowed_providers',);
                            if ('/' === substr($pathinfo, -1)) {
                                // no-op
                            } elseif ('GET' !== $canonicalMethod) {
                                goto not_bs_idp_archivist_json_allowed_providers;
                            } else {
                                return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_json_allowed_providers'));
                            }

                            return $ret;
                        }
                        not_bs_idp_archivist_json_allowed_providers:

                    }

                    elseif (0 === strpos($pathinfo, '/archive/archivist/manage')) {
                        // bs_idp_archivist_manage_user_wants
                        if ('/archive/archivist/manageuserwants' === $trimmedPathinfo) {
                            $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistController::manageuserwantsAction',  '_route' => 'bs_idp_archivist_manage_user_wants',);
                            if ('/' === substr($pathinfo, -1)) {
                                // no-op
                            } elseif ('GET' !== $canonicalMethod) {
                                goto not_bs_idp_archivist_manage_user_wants;
                            } else {
                                return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_manage_user_wants'));
                            }

                            return $ret;
                        }
                        not_bs_idp_archivist_manage_user_wants:

                        // bs_idp_archivist_manage_provider_wants
                        if ('/archive/archivist/manageproviderwants' === $trimmedPathinfo) {
                            $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistController::manageproviderwantsAction',  '_route' => 'bs_idp_archivist_manage_provider_wants',);
                            if ('/' === substr($pathinfo, -1)) {
                                // no-op
                            } elseif ('GET' !== $canonicalMethod) {
                                goto not_bs_idp_archivist_manage_provider_wants;
                            } else {
                                return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_manage_provider_wants'));
                            }

                            return $ret;
                        }
                        not_bs_idp_archivist_manage_provider_wants:

                        if (0 === strpos($pathinfo, '/archive/archivist/managedb_input_')) {
                            // bs_idp_archivist_managedb_input_services
                            if ('/archive/archivist/managedb_input_services' === $trimmedPathinfo) {
                                $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistController::managedbinputservicesAction',  '_route' => 'bs_idp_archivist_managedb_input_services',);
                                if ('/' === substr($pathinfo, -1)) {
                                    // no-op
                                } elseif ('GET' !== $canonicalMethod) {
                                    goto not_bs_idp_archivist_managedb_input_services;
                                } else {
                                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_managedb_input_services'));
                                }

                                return $ret;
                            }
                            not_bs_idp_archivist_managedb_input_services:

                            if (0 === strpos($pathinfo, '/archive/archivist/managedb_input_legalentities')) {
                                // bs_idp_archivist_managedb_input_legalentities
                                if ('/archive/archivist/managedb_input_legalentities' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistController::managedbinputlegalentitiesAction',  '_route' => 'bs_idp_archivist_managedb_input_legalentities',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_managedb_input_legalentities;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_managedb_input_legalentities'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_managedb_input_legalentities:

                                // bs_idp_archivist_managedb_input_legalentities_finetune
                                if ('/archive/archivist/managedb_input_legalentities_finetune' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistController::managedbinputlegalentitiesfinetuneAction',  '_route' => 'bs_idp_archivist_managedb_input_legalentities_finetune',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_managedb_input_legalentities_finetune;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_managedb_input_legalentities_finetune'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_managedb_input_legalentities_finetune:

                            }

                            elseif (0 === strpos($pathinfo, '/archive/archivist/managedb_input_localizations')) {
                                // bs_idp_archivist_managedb_input_localizations
                                if ('/archive/archivist/managedb_input_localizations' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistController::managedbinputlocalizationsAction',  '_route' => 'bs_idp_archivist_managedb_input_localizations',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_managedb_input_localizations;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_managedb_input_localizations'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_managedb_input_localizations:

                                // bs_idp_archivist_managedb_input_localizations_finetune
                                if ('/archive/archivist/managedb_input_localizations_finetune' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistController::managedbinputlocalizationsfinetuneAction',  '_route' => 'bs_idp_archivist_managedb_input_localizations_finetune',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_managedb_input_localizations_finetune;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_managedb_input_localizations_finetune'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_managedb_input_localizations_finetune:

                            }

                            elseif (0 === strpos($pathinfo, '/archive/archivist/managedb_input_budgetcodes')) {
                                // bs_idp_archivist_managedb_input_budgetcodes
                                if ('/archive/archivist/managedb_input_budgetcodes' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistController::managedbinputbudgetcodesAction',  '_route' => 'bs_idp_archivist_managedb_input_budgetcodes',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_managedb_input_budgetcodes;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_managedb_input_budgetcodes'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_managedb_input_budgetcodes:

                                // bs_idp_archivist_managedb_input_budgetcodes_finetune
                                if ('/archive/archivist/managedb_input_budgetcodes_finetune' === $trimmedPathinfo) {
                                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistController::managedbinputbudgetcodesfinetuneAction',  '_route' => 'bs_idp_archivist_managedb_input_budgetcodes_finetune',);
                                    if ('/' === substr($pathinfo, -1)) {
                                        // no-op
                                    } elseif ('GET' !== $canonicalMethod) {
                                        goto not_bs_idp_archivist_managedb_input_budgetcodes_finetune;
                                    } else {
                                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_managedb_input_budgetcodes_finetune'));
                                    }

                                    return $ret;
                                }
                                not_bs_idp_archivist_managedb_input_budgetcodes_finetune:

                            }

                            elseif (0 === strpos($pathinfo, '/archive/archivist/managedb_input_d')) {
                                if (0 === strpos($pathinfo, '/archive/archivist/managedb_input_documentnatures')) {
                                    // bs_idp_archivist_managedb_input_documentnatures
                                    if ('/archive/archivist/managedb_input_documentnatures' === $trimmedPathinfo) {
                                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistController::managedbinputdocumentnaturesAction',  '_route' => 'bs_idp_archivist_managedb_input_documentnatures',);
                                        if ('/' === substr($pathinfo, -1)) {
                                            // no-op
                                        } elseif ('GET' !== $canonicalMethod) {
                                            goto not_bs_idp_archivist_managedb_input_documentnatures;
                                        } else {
                                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_managedb_input_documentnatures'));
                                        }

                                        return $ret;
                                    }
                                    not_bs_idp_archivist_managedb_input_documentnatures:

                                    // bs_idp_archivist_managedb_input_documentnatures_finetune
                                    if ('/archive/archivist/managedb_input_documentnatures_finetune' === $trimmedPathinfo) {
                                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistController::managedbinputdocumentnaturesfinetuneAction',  '_route' => 'bs_idp_archivist_managedb_input_documentnatures_finetune',);
                                        if ('/' === substr($pathinfo, -1)) {
                                            // no-op
                                        } elseif ('GET' !== $canonicalMethod) {
                                            goto not_bs_idp_archivist_managedb_input_documentnatures_finetune;
                                        } else {
                                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_managedb_input_documentnatures_finetune'));
                                        }

                                        return $ret;
                                    }
                                    not_bs_idp_archivist_managedb_input_documentnatures_finetune:

                                }

                                elseif (0 === strpos($pathinfo, '/archive/archivist/managedb_input_documenttypes')) {
                                    // bs_idp_archivist_managedb_input_documenttypes
                                    if ('/archive/archivist/managedb_input_documenttypes' === $trimmedPathinfo) {
                                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistController::managedbinputdocumenttypesAction',  '_route' => 'bs_idp_archivist_managedb_input_documenttypes',);
                                        if ('/' === substr($pathinfo, -1)) {
                                            // no-op
                                        } elseif ('GET' !== $canonicalMethod) {
                                            goto not_bs_idp_archivist_managedb_input_documenttypes;
                                        } else {
                                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_managedb_input_documenttypes'));
                                        }

                                        return $ret;
                                    }
                                    not_bs_idp_archivist_managedb_input_documenttypes:

                                    // bs_idp_archivist_managedb_input_documenttypes_finetune
                                    if ('/archive/archivist/managedb_input_documenttypes_finetune' === $trimmedPathinfo) {
                                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistController::managedbinputdocumenttypesfinetuneAction',  '_route' => 'bs_idp_archivist_managedb_input_documenttypes_finetune',);
                                        if ('/' === substr($pathinfo, -1)) {
                                            // no-op
                                        } elseif ('GET' !== $canonicalMethod) {
                                            goto not_bs_idp_archivist_managedb_input_documenttypes_finetune;
                                        } else {
                                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_managedb_input_documenttypes_finetune'));
                                        }

                                        return $ret;
                                    }
                                    not_bs_idp_archivist_managedb_input_documenttypes_finetune:

                                }

                                elseif (0 === strpos($pathinfo, '/archive/archivist/managedb_input_de')) {
                                    if (0 === strpos($pathinfo, '/archive/archivist/managedb_input_descriptions1')) {
                                        // bs_idp_archivist_managedb_input_descriptions1
                                        if ('/archive/archivist/managedb_input_descriptions1' === $trimmedPathinfo) {
                                            $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistController::managedbinputdescriptions1Action',  '_route' => 'bs_idp_archivist_managedb_input_descriptions1',);
                                            if ('/' === substr($pathinfo, -1)) {
                                                // no-op
                                            } elseif ('GET' !== $canonicalMethod) {
                                                goto not_bs_idp_archivist_managedb_input_descriptions1;
                                            } else {
                                                return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_managedb_input_descriptions1'));
                                            }

                                            return $ret;
                                        }
                                        not_bs_idp_archivist_managedb_input_descriptions1:

                                        // bs_idp_archivist_managedb_input_descriptions1_finetune
                                        if ('/archive/archivist/managedb_input_descriptions1_finetune' === $trimmedPathinfo) {
                                            $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistController::managedbinputdescriptions1finetuneAction',  '_route' => 'bs_idp_archivist_managedb_input_descriptions1_finetune',);
                                            if ('/' === substr($pathinfo, -1)) {
                                                // no-op
                                            } elseif ('GET' !== $canonicalMethod) {
                                                goto not_bs_idp_archivist_managedb_input_descriptions1_finetune;
                                            } else {
                                                return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_managedb_input_descriptions1_finetune'));
                                            }

                                            return $ret;
                                        }
                                        not_bs_idp_archivist_managedb_input_descriptions1_finetune:

                                    }

                                    elseif (0 === strpos($pathinfo, '/archive/archivist/managedb_input_descriptions2')) {
                                        // bs_idp_archivist_managedb_input_descriptions2
                                        if ('/archive/archivist/managedb_input_descriptions2' === $trimmedPathinfo) {
                                            $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistController::managedbinputdescriptions2Action',  '_route' => 'bs_idp_archivist_managedb_input_descriptions2',);
                                            if ('/' === substr($pathinfo, -1)) {
                                                // no-op
                                            } elseif ('GET' !== $canonicalMethod) {
                                                goto not_bs_idp_archivist_managedb_input_descriptions2;
                                            } else {
                                                return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_managedb_input_descriptions2'));
                                            }

                                            return $ret;
                                        }
                                        not_bs_idp_archivist_managedb_input_descriptions2:

                                        // bs_idp_archivist_managedb_input_descriptions2_finetune
                                        if ('/archive/archivist/managedb_input_descriptions2_finetune' === $trimmedPathinfo) {
                                            $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistController::managedbinputdescriptions2finetuneAction',  '_route' => 'bs_idp_archivist_managedb_input_descriptions2_finetune',);
                                            if ('/' === substr($pathinfo, -1)) {
                                                // no-op
                                            } elseif ('GET' !== $canonicalMethod) {
                                                goto not_bs_idp_archivist_managedb_input_descriptions2_finetune;
                                            } else {
                                                return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_managedb_input_descriptions2_finetune'));
                                            }

                                            return $ret;
                                        }
                                        not_bs_idp_archivist_managedb_input_descriptions2_finetune:

                                    }

                                    // bs_idp_archivist_managedb_input_deliveraddress
                                    if ('/archive/archivist/managedb_input_deliveraddress' === $trimmedPathinfo) {
                                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistController::managedbinputdeliveraddressAction',  '_route' => 'bs_idp_archivist_managedb_input_deliveraddress',);
                                        if ('/' === substr($pathinfo, -1)) {
                                            // no-op
                                        } elseif ('GET' !== $canonicalMethod) {
                                            goto not_bs_idp_archivist_managedb_input_deliveraddress;
                                        } else {
                                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_managedb_input_deliveraddress'));
                                        }

                                        return $ret;
                                    }
                                    not_bs_idp_archivist_managedb_input_deliveraddress:

                                }

                            }

                        }

                        elseif (0 === strpos($pathinfo, '/archive/archivist/managedb_providers')) {
                            // bs_idp_archivist_managedb_providers
                            if ('/archive/archivist/managedb_providers' === $trimmedPathinfo) {
                                $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistController::managedbprovidersAction',  '_route' => 'bs_idp_archivist_managedb_providers',);
                                if ('/' === substr($pathinfo, -1)) {
                                    // no-op
                                } elseif ('GET' !== $canonicalMethod) {
                                    goto not_bs_idp_archivist_managedb_providers;
                                } else {
                                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_managedb_providers'));
                                }

                                return $ret;
                            }
                            not_bs_idp_archivist_managedb_providers:

                            // bs_idp_archivist_managedb_providers_finetune
                            if ('/archive/archivist/managedb_providers_finetune' === $trimmedPathinfo) {
                                $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistController::managedbprovidersfinetuneAction',  '_route' => 'bs_idp_archivist_managedb_providers_finetune',);
                                if ('/' === substr($pathinfo, -1)) {
                                    // no-op
                                } elseif ('GET' !== $canonicalMethod) {
                                    goto not_bs_idp_archivist_managedb_providers_finetune;
                                } else {
                                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_managedb_providers_finetune'));
                                }

                                return $ret;
                            }
                            not_bs_idp_archivist_managedb_providers_finetune:

                        }

                    }

                    // bs_idp_archivist_close_user_wants
                    if ('/archive/archivist/closeuserwants' === $trimmedPathinfo) {
                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistController::closeuserwantsAction',  '_route' => 'bs_idp_archivist_close_user_wants',);
                        if ('/' === substr($pathinfo, -1)) {
                            // no-op
                        } elseif ('GET' !== $canonicalMethod) {
                            goto not_bs_idp_archivist_close_user_wants;
                        } else {
                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_close_user_wants'));
                        }

                        return $ret;
                    }
                    not_bs_idp_archivist_close_user_wants:

                    // bs_idp_archivist_askunlimited_screen
                    if ('/archive/archivist/askunlimited' === $trimmedPathinfo) {
                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchivistController::askUnlimitedScreenAction',  '_route' => 'bs_idp_archivist_askunlimited_screen',);
                        if ('/' === substr($pathinfo, -1)) {
                            // no-op
                        } elseif ('GET' !== $canonicalMethod) {
                            goto not_bs_idp_archivist_askunlimited_screen;
                        } else {
                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_askunlimited_screen'));
                        }

                        return $ret;
                    }
                    not_bs_idp_archivist_askunlimited_screen:

                }

                elseif (0 === strpos($pathinfo, '/archive/ajax')) {
                    // bs_idp_archive_delete_ajax
                    if ('/archive/ajax/delete' === $pathinfo) {
                        return array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchiveJsonController::deleteAction',  '_route' => 'bs_idp_archive_delete_ajax',);
                    }

                    // bs_idp_archive_detailled_information_ajax
                    if ('/archive/ajax/detailledinformation' === $pathinfo) {
                        return array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchiveJsonController::detailledinformationAction',  '_route' => 'bs_idp_archive_detailled_information_ajax',);
                    }

                    // bs_idp_archive_modify_ajax
                    if ('/archive/ajax/modify' === $pathinfo) {
                        return array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchiveJsonController::modifyAction',  '_route' => 'bs_idp_archive_modify_ajax',);
                    }

                    // bs_idp_archive_search_ajax
                    if ('/archive/ajax/search' === $pathinfo) {
                        return array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchiveJsonController::searchAction',  '_route' => 'bs_idp_archive_search_ajax',);
                    }

                }

                elseif (0 === strpos($pathinfo, '/archive/ask')) {
                    // bs_idp_archive_askconsult_screen
                    if ('/archive/askconsult' === $pathinfo) {
                        return array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\DefaultController::askconsultScreenAction',  '_route' => 'bs_idp_archive_askconsult_screen',);
                    }

                    // bs_idp_archive_askreturn_screen
                    if ('/archive/askreturn' === $pathinfo) {
                        return array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\DefaultController::askreturnScreenAction',  '_route' => 'bs_idp_archive_askreturn_screen',);
                    }

                    // bs_idp_archive_askreloc_screen
                    if ('/archive/askreloc' === $pathinfo) {
                        return array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\DefaultController::askrelocScreenAction',  '_route' => 'bs_idp_archive_askreloc_screen',);
                    }

                    // bs_idp_archive_askexit_screen
                    if ('/archive/askexit' === $pathinfo) {
                        return array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\DefaultController::askexitScreenAction',  '_route' => 'bs_idp_archive_askexit_screen',);
                    }

                    // bs_idp_archive_askdelete_screen
                    if ('/archive/askdelete' === $pathinfo) {
                        return array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\DefaultController::askdeleteScreenAction',  '_route' => 'bs_idp_archive_askdelete_screen',);
                    }

                }

            }

            elseif (0 === strpos($pathinfo, '/archive/statistics')) {
                // bs_idp_statistics_mainview
                if ('/archive/statistics/mainview' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\StatisticsController::mainviewAction',  '_route' => 'bs_idp_statistics_mainview',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_idp_statistics_mainview;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_statistics_mainview'));
                    }

                    return $ret;
                }
                not_bs_idp_statistics_mainview:

                // bs_idp_statistics_askdatas
                if ('/archive/statistics/askdatas' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\StatisticsController::askdatasAction',  '_route' => 'bs_idp_statistics_askdatas',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_idp_statistics_askdatas;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_statistics_askdatas'));
                    }

                    return $ret;
                }
                not_bs_idp_statistics_askdatas:

                // bs_idp_statistics_detailledview
                if ('/archive/statistics/detailledview' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\StatisticsController::detailledviewAction',  '_route' => 'bs_idp_statistics_detailledview',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_idp_statistics_detailledview;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_statistics_detailledview'));
                    }

                    return $ret;
                }
                not_bs_idp_statistics_detailledview:

                // bs_idp_statistics_generatevalues
                if ('/archive/statistics/generate' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\StatisticsController::generateAction',  '_route' => 'bs_idp_statistics_generatevalues',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_idp_statistics_generatevalues;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_statistics_generatevalues'));
                    }

                    return $ret;
                }
                not_bs_idp_statistics_generatevalues:

            }

            // bs_idp_archive_search
            if ('/archive/search' === $pathinfo) {
                return array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\DefaultController::searchAction',  '_route' => 'bs_idp_archive_search',);
            }

            if (0 === strpos($pathinfo, '/archive/exportimport')) {
                if (0 === strpos($pathinfo, '/archive/exportimport/export')) {
                    // bs_idp_archive_export
                    if ('/archive/exportimport/export' === $trimmedPathinfo) {
                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ExportImportController::exportAction',  '_route' => 'bs_idp_archive_export',);
                        if ('/' === substr($pathinfo, -1)) {
                            // no-op
                        } elseif ('GET' !== $canonicalMethod) {
                            goto not_bs_idp_archive_export;
                        } else {
                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archive_export'));
                        }

                        return $ret;
                    }
                    not_bs_idp_archive_export:

                    // bs_idp_archive_export_offline
                    if ('/archive/exportimport/exportoffline' === $trimmedPathinfo) {
                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ExportImportController::exportOfflineAction',  '_route' => 'bs_idp_archive_export_offline',);
                        if ('/' === substr($pathinfo, -1)) {
                            // no-op
                        } elseif ('GET' !== $canonicalMethod) {
                            goto not_bs_idp_archive_export_offline;
                        } else {
                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archive_export_offline'));
                        }

                        return $ret;
                    }
                    not_bs_idp_archive_export_offline:

                    if (0 === strpos($pathinfo, '/archive/exportimport/exportall')) {
                        // bs_idp_export_all
                        if ('/archive/exportimport/exportall' === $trimmedPathinfo) {
                            $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ExportImportController::doExportAllAction',  '_route' => 'bs_idp_export_all',);
                            if ('/' === substr($pathinfo, -1)) {
                                // no-op
                            } elseif ('GET' !== $canonicalMethod) {
                                goto not_bs_idp_export_all;
                            } else {
                                return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_export_all'));
                            }

                            return $ret;
                        }
                        not_bs_idp_export_all:

                        // bs_idp_export_all_offline
                        if ('/archive/exportimport/exportalloffline' === $trimmedPathinfo) {
                            $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ExportImportController::exportAllOfflineAction',  '_route' => 'bs_idp_export_all_offline',);
                            if ('/' === substr($pathinfo, -1)) {
                                // no-op
                            } elseif ('GET' !== $canonicalMethod) {
                                goto not_bs_idp_export_all_offline;
                            } else {
                                return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_export_all_offline'));
                            }

                            return $ret;
                        }
                        not_bs_idp_export_all_offline:

                    }

                }

                // bs_idp_archive_partialimportscreen
                if ('/archive/exportimport/partialimportscreen' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ExportImportController::partialimportscreenAction',  '_route' => 'bs_idp_archive_partialimportscreen',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_idp_archive_partialimportscreen;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archive_partialimportscreen'));
                    }

                    return $ret;
                }
                not_bs_idp_archive_partialimportscreen:

                // bs_idp_archive_partialimportdo
                if ('/archive/exportimport/partialimportdo' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ExportImportController::partialimportdoAction',  '_route' => 'bs_idp_archive_partialimportdo',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_idp_archive_partialimportdo;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archive_partialimportdo'));
                    }

                    return $ret;
                }
                not_bs_idp_archive_partialimportdo:

                if (0 === strpos($pathinfo, '/archive/exportimport/import')) {
                    if (0 === strpos($pathinfo, '/archive/exportimport/importtreatment')) {
                        // bs_idp_archive_partialimporttreatmentscreen
                        if ('/archive/exportimport/importtreatmentscreen' === $trimmedPathinfo) {
                            $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ExportImportController::importtreatmentscreenAction',  '_route' => 'bs_idp_archive_partialimporttreatmentscreen',);
                            if ('/' === substr($pathinfo, -1)) {
                                // no-op
                            } elseif ('GET' !== $canonicalMethod) {
                                goto not_bs_idp_archive_partialimporttreatmentscreen;
                            } else {
                                return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archive_partialimporttreatmentscreen'));
                            }

                            return $ret;
                        }
                        not_bs_idp_archive_partialimporttreatmentscreen:

                        // bs_idp_archive_importtreatmentsee
                        if (0 === strpos($pathinfo, '/archive/exportimport/importtreatmentsee') && preg_match('#^/archive/exportimport/importtreatmentsee/(?P<id>[^/]++)$#sD', $pathinfo, $matches)) {
                            return $this->mergeDefaults(array_replace($matches, ['_route' => 'bs_idp_archive_importtreatmentsee']), array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ExportImportController::importtreatmentseeAction',));
                        }

                        // bs_idp_archive_importtreatmentdo
                        if ('/archive/exportimport/importtreatmentdo' === $trimmedPathinfo) {
                            $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ExportImportController::importtreatmentdoAction',  '_route' => 'bs_idp_archive_importtreatmentdo',);
                            if ('/' === substr($pathinfo, -1)) {
                                // no-op
                            } elseif ('GET' !== $canonicalMethod) {
                                goto not_bs_idp_archive_importtreatmentdo;
                            } else {
                                return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archive_importtreatmentdo'));
                            }

                            return $ret;
                        }
                        not_bs_idp_archive_importtreatmentdo:

                    }

                    // bs_idp_archive_importsrapportsee
                    if ('/archive/exportimport/importsrapportsee' === $trimmedPathinfo) {
                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ExportImportController::importsrapportseeAction',  '_route' => 'bs_idp_archive_importsrapportsee',);
                        if ('/' === substr($pathinfo, -1)) {
                            // no-op
                        } elseif ('GET' !== $canonicalMethod) {
                            goto not_bs_idp_archive_importsrapportsee;
                        } else {
                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archive_importsrapportsee'));
                        }

                        return $ret;
                    }
                    not_bs_idp_archive_importsrapportsee:

                    // bs_idp_archive_importrapportfile
                    if (0 === strpos($pathinfo, '/archive/exportimport/importrapportfile') && preg_match('#^/archive/exportimport/importrapportfile/(?P<id>[^/]++)$#sD', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, ['_route' => 'bs_idp_archive_importrapportfile']), array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ExportImportController::importrapportfileAction',));
                    }

                    // bs_idp_archive_importerrorfile
                    if (0 === strpos($pathinfo, '/archive/exportimport/importerrorfile') && preg_match('#^/archive/exportimport/importerrorfile/(?P<id>[^/]++)$#sD', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, ['_route' => 'bs_idp_archive_importerrorfile']), array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ExportImportController::importerrorfileAction',));
                    }

                    // bs_idp_archive_importcancel
                    if (0 === strpos($pathinfo, '/archive/exportimport/importcancel') && preg_match('#^/archive/exportimport/importcancel/(?P<id>[^/]++)$#sD', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, ['_route' => 'bs_idp_archive_importcancel']), array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ExportImportController::importcancelAction',));
                    }

                    // bs_idp_archive_importvalidate
                    if (0 === strpos($pathinfo, '/archive/exportimport/importvalidate') && preg_match('#^/archive/exportimport/importvalidate/(?P<id>[^/]++)$#sD', $pathinfo, $matches)) {
                        return $this->mergeDefaults(array_replace($matches, ['_route' => 'bs_idp_archive_importvalidate']), array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ExportImportController::importvalidateAction',));
                    }

                }

                // bs_idp_archive_ajaximporttreatmentsurvey
                if ('/archive/exportimport/ajaximporttreatmentsurvey' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ExportImportController::ajaximporttreatmentsurveyAction',  '_route' => 'bs_idp_archive_ajaximporttreatmentsurvey',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_idp_archive_ajaximporttreatmentsurvey;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archive_ajaximporttreatmentsurvey'));
                    }

                    return $ret;
                }
                not_bs_idp_archive_ajaximporttreatmentsurvey:

            }

            elseif (0 === strpos($pathinfo, '/archive/print')) {
                if (0 === strpos($pathinfo, '/archive/print/tag')) {
                    // bs_idp_archive_print_tag
                    if ('/archive/print/tag' === $trimmedPathinfo) {
                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\PrintController::printTagAction',  '_route' => 'bs_idp_archive_print_tag',);
                        if ('/' === substr($pathinfo, -1)) {
                            // no-op
                        } elseif ('GET' !== $canonicalMethod) {
                            goto not_bs_idp_archive_print_tag;
                        } else {
                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archive_print_tag'));
                        }

                        return $ret;
                    }
                    not_bs_idp_archive_print_tag:

                    // bs_idp_archive_print_tags
                    if ('/archive/print/tags' === $trimmedPathinfo) {
                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\PrintController::printTagsAction',  '_route' => 'bs_idp_archive_print_tags',);
                        if ('/' === substr($pathinfo, -1)) {
                            // no-op
                        } elseif ('GET' !== $canonicalMethod) {
                            goto not_bs_idp_archive_print_tags;
                        } else {
                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archive_print_tags'));
                        }

                        return $ret;
                    }
                    not_bs_idp_archive_print_tags:

                }

                elseif (0 === strpos($pathinfo, '/archive/print/table')) {
                    // bs_idp_archive_print_table
                    if ('/archive/print/table' === $trimmedPathinfo) {
                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\PrintController::printTableAction',  '_route' => 'bs_idp_archive_print_table',);
                        if ('/' === substr($pathinfo, -1)) {
                            // no-op
                        } elseif ('GET' !== $canonicalMethod) {
                            goto not_bs_idp_archive_print_table;
                        } else {
                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archive_print_table'));
                        }

                        return $ret;
                    }
                    not_bs_idp_archive_print_table:

                    // bs_idp_archive_print_table_offline
                    if ('/archive/print/table_offline' === $trimmedPathinfo) {
                        $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\PrintController::printTableOfflineAction',  '_route' => 'bs_idp_archive_print_table_offline',);
                        if ('/' === substr($pathinfo, -1)) {
                            // no-op
                        } elseif ('GET' !== $canonicalMethod) {
                            goto not_bs_idp_archive_print_table_offline;
                        } else {
                            return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archive_print_table_offline'));
                        }

                        return $ret;
                    }
                    not_bs_idp_archive_print_table_offline:

                }

                // bs_idp_archive_print_sheet
                if ('/archive/print/sheet' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\PrintController::printSheetAction',  '_route' => 'bs_idp_archive_print_sheet',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_idp_archive_print_sheet;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archive_print_sheet'));
                    }

                    return $ret;
                }
                not_bs_idp_archive_print_sheet:

                // bs_idp_archivist_print_provider_connector
                if ('/archive/print/providerconnector' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\PrintController::printProviderConnectorAction',  '_route' => 'bs_idp_archivist_print_provider_connector',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_idp_archivist_print_provider_connector;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archivist_print_provider_connector'));
                    }

                    return $ret;
                }
                not_bs_idp_archivist_print_provider_connector:

            }

            elseif (0 === strpos($pathinfo, '/archive/reconciliation')) {
                // bs_idp_archive_reconciliation_index
                if ('/archive/reconciliation/index' === $pathinfo) {
                    return array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ReconciliationController::indexAction',  '_route' => 'bs_idp_archive_reconciliation_index',);
                }

                // bs_idp_archive_reconciliation_status
                if ('/archive/reconciliation/status' === $pathinfo) {
                    return array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ReconciliationController::statusAction',  '_route' => 'bs_idp_archive_reconciliation_status',);
                }

                // bs_idp_archive_reconciliation_setstatus
                if ('/archive/reconciliation/setstatus' === $pathinfo) {
                    return array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ReconciliationController::setStatusAction',  '_route' => 'bs_idp_archive_reconciliation_setstatus',);
                }

                // bs_idp_archive_reconciliation_result
                if ('/archive/reconciliation/result' === $pathinfo) {
                    return array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ReconciliationController::resultAction',  '_route' => 'bs_idp_archive_reconciliation_result',);
                }

                // bs_idp_archive_reconciliation_reset
                if ('/archive/reconciliation/reset' === $pathinfo) {
                    return array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ReconciliationController::resetAction',  '_route' => 'bs_idp_archive_reconciliation_reset',);
                }

                // bs_idp_archive_reconciliation_error
                if ('/archive/reconciliation/error' === $pathinfo) {
                    return array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ReconciliationController::errorAction',  '_route' => 'bs_idp_archive_reconciliation_error',);
                }

                // bs_idp_archive_reconciliation_upload
                if ('/archive/reconciliation/upload' === $pathinfo) {
                    return array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ReconciliationController::uploadAction',  '_route' => 'bs_idp_archive_reconciliation_upload',);
                }

                // bs_idp_archive_reconciliation_getstatus
                if ('/archive/reconciliation/getstatus' === $pathinfo) {
                    return array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ReconciliationController::getStatusAction',  '_route' => 'bs_idp_archive_reconciliation_getstatus',);
                }

            }

            // bs_idp_archive_new
            if ('/archive/new' === $trimmedPathinfo) {
                $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\DefaultController::newAction',  '_route' => 'bs_idp_archive_new',);
                if ('/' === substr($pathinfo, -1)) {
                    // no-op
                } elseif ('GET' !== $canonicalMethod) {
                    goto not_bs_idp_archive_new;
                } else {
                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archive_new'));
                }

                return $ret;
            }
            not_bs_idp_archive_new:

            // bs_idp_archive_view
            if ('/archive/view' === $trimmedPathinfo) {
                $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\DefaultController::viewAction',  '_route' => 'bs_idp_archive_view',);
                if ('/' === substr($pathinfo, -1)) {
                    // no-op
                } elseif ('GET' !== $canonicalMethod) {
                    goto not_bs_idp_archive_view;
                } else {
                    return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archive_view'));
                }

                return $ret;
            }
            not_bs_idp_archive_view:

            if (0 === strpos($pathinfo, '/archive/do')) {
                // bs_idp_archive_donew
                if ('/archive/donew' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\DefaultController::donewAction',  '_route' => 'bs_idp_archive_donew',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_idp_archive_donew;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archive_donew'));
                    }

                    return $ret;
                }
                not_bs_idp_archive_donew:

                // bs_idp_archive_domodify
                if ('/archive/domodify' === $trimmedPathinfo) {
                    $ret = array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\DefaultController::domodifyAction',  '_route' => 'bs_idp_archive_domodify',);
                    if ('/' === substr($pathinfo, -1)) {
                        // no-op
                    } elseif ('GET' !== $canonicalMethod) {
                        goto not_bs_idp_archive_domodify;
                    } else {
                        return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_archive_domodify'));
                    }

                    return $ret;
                }
                not_bs_idp_archive_domodify:

                // bs_idp_archive_transferaction
                if ('/archive/docusttransfer' === $pathinfo) {
                    return array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\DefaultController::doCustomerTransferAction',  '_route' => 'bs_idp_archive_transferaction',);
                }

                if (0 === strpos($pathinfo, '/archive/doask')) {
                    // bs_idp_archive_askconsult_action
                    if ('/archive/doaskconsult' === $pathinfo) {
                        return array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\DefaultController::doAskConsultAction',  '_route' => 'bs_idp_archive_askconsult_action',);
                    }

                    // bs_idp_archive_askreturn_action
                    if ('/archive/doaskreturn' === $pathinfo) {
                        return array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\DefaultController::doAskReturnAction',  '_route' => 'bs_idp_archive_askreturn_action',);
                    }

                    // bs_idp_archive_askreloc_action
                    if ('/archive/doaskreloc' === $pathinfo) {
                        return array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\DefaultController::doAskRelocAction',  '_route' => 'bs_idp_archive_askreloc_action',);
                    }

                    // bs_idp_archive_askexit_action
                    if ('/archive/doaskexit' === $pathinfo) {
                        return array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\DefaultController::doAskExitAction',  '_route' => 'bs_idp_archive_askexit_action',);
                    }

                    // bs_idp_archive_askdelete_action
                    if ('/archive/doaskdelete' === $pathinfo) {
                        return array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\DefaultController::doAskDeleteAction',  '_route' => 'bs_idp_archive_askdelete_action',);
                    }

                }

            }

            // bs_idp_archive_modify
            if (0 === strpos($pathinfo, '/archive/modify') && preg_match('#^/archive/modify/(?P<archiveId>[^/]++)$#sD', $pathinfo, $matches)) {
                return $this->mergeDefaults(array_replace($matches, ['_route' => 'bs_idp_archive_modify']), array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\DefaultController::modifyAction',));
            }

            // bs_idp_archive_transferscreen
            if ('/archive/transfer' === $pathinfo) {
                return array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\DefaultController::transferScreenAction',  '_route' => 'bs_idp_archive_transferscreen',);
            }

            // bs_idp_archive_updatefield_json
            if ('/archive/json/updatefield' === $pathinfo) {
                return array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchiveJsonController::updatefieldAction',  '_route' => 'bs_idp_archive_updatefield_json',);
            }

            // bs_idp_archive_form_initlists
            if ('/archive/get_json/forminitlists' === $pathinfo) {
                return array (  '_controller' => 'bs\\IDP\\ArchiveBundle\\Controller\\ArchiveJsonController::forminitlistsAction',  '_route' => 'bs_idp_archive_form_initlists',);
            }

        }

        // bs_idp_dashboard_homepage
        if ('' === $trimmedPathinfo) {
            $ret = array (  '_controller' => 'bs\\IDP\\DashboardBundle\\Controller\\DefaultController::indexAction',  '_route' => 'bs_idp_dashboard_homepage',);
            if ('/' === substr($pathinfo, -1)) {
                // no-op
            } elseif ('GET' !== $canonicalMethod) {
                goto not_bs_idp_dashboard_homepage;
            } else {
                return array_replace($ret, $this->redirect($rawPathinfo.'/', 'bs_idp_dashboard_homepage'));
            }

            return $ret;
        }
        not_bs_idp_dashboard_homepage:

        // homepage
        if ('' === $trimmedPathinfo) {
            $ret = array (  '_controller' => 'AppBundle\\Controller\\DefaultController::indexAction',  '_route' => 'homepage',);
            if ('/' === substr($pathinfo, -1)) {
                // no-op
            } elseif ('GET' !== $canonicalMethod) {
                goto not_homepage;
            } else {
                return array_replace($ret, $this->redirect($rawPathinfo.'/', 'homepage'));
            }

            return $ret;
        }
        not_homepage:

        if ('/' === $pathinfo && !$allow) {
            throw new Symfony\Component\Routing\Exception\NoConfigurationException();
        }

        throw 0 < count($allow) ? new MethodNotAllowedException(array_unique($allow)) : new ResourceNotFoundException();
    }
}
