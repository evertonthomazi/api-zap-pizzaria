<!DOCTYPE html>
<html lang="pt_br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <meta name="author" content="samoel duarte">
    <title>Painel Admin - @yield('title')</title>

    <!-- Custom fonts for this template-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;300;400;500;700;900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Signika:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.3.0/css/bootstrap-colorpicker.min.css" />


    <!-- Custom styles for this template-->
    <link href="{{ asset('/assets/admin/css/styles.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/assets/admin/css/edit.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    @yield('css')



</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/dispositivo">
                <div class="sidebar-brand-icon ">Benjamin</div>
            </a>
            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            <!-- Heading -->
            <div class="sidebar-heading">
                Pedidos
            </div>

            <!-- Na sidebar -->
            @if (session('authenticated') && session('userData') && session('userData')->role == 'admin')
                <li class="nav-item">
                    <a href="{{ route('admin.order.index') }}" class="nav-link">
                        <i class="fas fa-users"></i>
                        <span>Pedidos</span>
                        <span class="badge badge-danger badge-counter"
                            id="sidebar-notification-count">{{ session('userData')->unreadNotifications->count() }}</span>
                    </a>
                </li>
            @endif



            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <li class="nav-item">
                <a href="{{ route('admin.product.index') }}" class="nav-link">
                    <i class="fas fa-users"></i>
                    <span>Produtos</span>
                </a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <li class="nav-item">
                <a href="{{ route('admin.customer.index') }}" class="nav-link">
                    <i class="fas fa-users"></i>
                    <span>Clientes</span>
                </a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <li class="nav-item">
                <a href="{{ route('admin.config.index') }}" class="nav-link">
                    <i class="far fa-images"></i>
                    <span>Configuração</span>
                </a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            <!-- Heading -->
            <div class="sidebar-heading">
                Conexões
            </div>
            <!-- Dispositivos -->
            <li class="nav-item">
                <a href="{{ route('admin.device.index') }}" class="nav-link">
                    <i class="far fa-images"></i>
                    <span>Dispositivos</span>
                </a>
            </li>



            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Heading -->
            <div class="sidebar-heading">
                Mensagens
            </div>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#message"
                    aria-expanded="true" aria-controls="message">
                    <i class="fas fa-mail-bulk"></i>
                    <span>Mensagem</span>
                </a>

                <div id="message" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{ route('admin.message.create') }}">Envio em Massa</a>
                        <a class="collapse-item" href="{{ route('admin.message.index') }}">Rolatório de Envio</a>
                        <a class="collapse-item" href="{{ route('admin.schedule.index') }}">Agendamentos</a>
                    </div>
                </div>

            </li>








            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->


        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <ul id="notificacoes">
                            <!-- Aqui é onde as notificações aparecerão -->
                        </ul>
                        <!-- No header, adicione o contador de notificações -->
                        @if (session('authenticated') && session('userData') && session('userData')->role == 'admin')
                            <li class="nav-item dropdown no-arrow mx-1">
                                <a class="nav-link dropdown-toggle" href="#" id="alertsOrder" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-bell fa-fw"></i>
                                    <!-- Counter - Alerts -->
                                    <span class="badge badge-danger badge-counter"
                                        id="notification-count">{{ session('userData')->unreadNotifications->count() }}</span>
                                </a>
                                <!-- Dropdown - Alerts -->
                                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                    aria-labelledby="alertsOrder">
                                    <h6 class="dropdown-header">Pedidos</h6>
                                    <div class="dropdown-orders">
                                        @foreach (session('userData')->unreadNotifications as $notification)
                                            <a class="dropdown-item d-flex align-items-center" href="#">
                                                <div class="mr-3">
                                                    <div class="icon-circle bg-primary">
                                                        <i class="fas fa-file-alt text-white"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="small text-gray-500">{{ $notification->created_at }}
                                                    </div>
                                                    <span
                                                        class="font-weight-bold">{{ $notification->data['message'] }}</span>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                    <a class="dropdown-item text-center small text-gray-500"
                                        href="{{ route('admin.notifications.index') }}">Ver todas as notificações</a>
                                </div>
                            </li>
                        @endif

                       

                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsMessage" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <!-- Counter - Messages -->
                                <span class="badge badge-danger badge-counter countMessages"></span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsMessage">
                                <h6 class="dropdown-header">Mensagens</h6>
                                <div class="dropdown-messages"></div>
                                <a class="dropdown-item text-center small text-gray-500" href="">Ver todas as
                                    mensagens</a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"></span>
                                <div class="icon-circle bg-primary">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Perfil
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Configurações
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/admin/sair">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Sair
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    @yield('content')
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <a href="https://betasolucao.com.br" target="_blank">
                            <span>Copyright &copy; Beta Solução {{ now()->year }}</span>
                        </a>

                    </div>
                </div>

            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    {{-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"> --}}
    {{-- </script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
    </script> --}}
    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('/assets/admin/vendor/jquery/jquery.min.js') }} "></script>
    <script src="{{ asset('/assets/admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }} "></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('/assets/admin/vendor/jquery-easing/jquery.easing.min.js') }} "></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/lang/summernote-pt-BR.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/i18n/pt-BR.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.3.0/js/bootstrap-colorpicker.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{ asset('/assets/admin/js/scripts.min.js') }}"></script>
    <script src="{{ asset('/assets/admin/js/main.js') }}"></script>
    <script src="{{ asset('/assets/utils.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.3/howler.min.js"></script>




    @yield('scripts')
    <script>
        verificarCondicao();
        navigator.mediaDevices.getUserMedia({
                audio: true
            })
            .then(function(stream) {
                // O usuário concedeu permissão para o uso do microfone
                // Agora você pode usar o stream de áudio.
            })
            .catch(function(error) {
                // O usuário negou a permissão ou ocorreu um erro.
            });
        var som = new Howl({
            src: ['/audioTelefone.mp3']
        });


        function verificarCondicao() {
            $.ajax({
                url: '/admin/chat/getAtendimentoPedente',
                method: 'GET',
                success: function(response) {

                    var audio = document.getElementById('myAudio');

                    if (response > '0') {

                          som.play();
                        document.querySelector('.notification-count').textContent = response;

                    } else {
                          som.pause();
                        document.querySelector('.notification-count').textContent = response;


                    }
                }
            });
        }

        // Executar a função de verificação a cada 5 segundos.
        setInterval(verificarCondicao, 5000);









        // let notificationSoundPlayed = false;

        // function checkNotifications() {
          
        //     $.ajax({
        //         url: '{{ route('admin.notifications.check') }}',
        //         method: 'GET',
        //         success: function(response) {
        //             console.log(response);
        //             if (response.notifications.length > 0 && !notificationSoundPlayed) {
        //                 playNotificationSound();
        //                 showNotificationAlert(response.notifications.length);
        //                 notificationSoundPlayed = true; // Marque que o som já foi tocado
        //             }
        //             updateNotificationCount(response.notifications.length);
        //             updateNotificationList(response.notifications);
        //         }
        //     });
        // }

        // function playNotificationSound() {
        //     const audio = new Howl({
        //         src: ['/audioTelefone.mp3'],
        //         onend: function() {
        //             audio.stop(); // Parar o áudio depois que ele tocar uma vez
        //         }
        //     });
        //     audio.play(); // Tocar o som de notificação uma vez
        // }

        // function showNotificationAlert(count) {
        //     Swal.fire({
        //         title: 'Novo Pedido!',
        //         text: `Você tem ${count} novo(s) pedido(s).`,
        //         icon: 'info',
        //         showCancelButton: true,
        //         confirmButtonText: 'Ver Pedidos',
        //         cancelButtonText: 'Fechar',
        //         customClass: {
        //             popup: 'animated shake'
        //         },
        //         didOpen: () => {
        //             const intervalId = setInterval(() => {
        //                 Swal.getPopup().classList.toggle('shake');
        //             }, 2000);

        //             Swal.getPopup().addEventListener('mouseleave', () => clearInterval(
        //                 intervalId)); // Limpar o intervalo quando o modal for fechado
        //         }
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             markNotificationsAsRead().then(() => {
        //                 window.location.href = '{{ route('admin.order.index2') }}';
        //             });
        //         }
        //         notificationSoundPlayed = false; // Reseta a flag ao fechar o modal
        //     });
        // }

        // function updateNotificationCount(count) {
        //     $('#notification-count').text(count);
        //     $('#sidebar-notification-count').text(count);
        // }

        // function updateNotificationList(notifications) {
        //     const notificationList = $('#notificacoes');
        //     notificationList.empty(); // Limpar a lista existente

        //     notifications.forEach(notification => {
        //         const notificationItem = $('<li class="dropdown-item d-flex align-items-center"></li>');
        //         notificationItem.html(`
        //             <div class="mr-3">
        //                 <div class="icon-circle bg-primary">
        //                     <i class="fas fa-file-alt text-white"></i>
        //                 </div>
        //             </div>
        //             <div>
        //                 <div class="small text-gray-500">${notification.created_at}</div>
        //                 <span class="font-weight-bold">${notification.data.message}</span>
        //             </div>
        //         `);
        //         notificationList.append(notificationItem);
        //     });
        // }

        // function markNotificationsAsRead() {
        //     return $.ajax({
        //         url: '{{ route('admin.notifications.markAllRead') }}',
        //         method: 'POST',
        //         headers: {
        //             'X-CSRF-TOKEN': '{{ csrf_token() }}'
        //         },
        //         success: function(response) {
        //             if (response.success) {
        //                 // Atualizar o contador de notificações no frontend
        //                 $('#notification-count').text('0');
        //                 $('#sidebar-notification-count').text('0');
        //                 $('#notificacoes').empty(); // Limpar as notificações após marcar como lidas
        //             }
        //         }
        //     });
        // }

        // // Verificar notificações a cada 5 segundos
        // setInterval(checkNotifications, 5000);

        // function showToast(icon, message) {
        //     const Toast = Swal.mixin({
        //         toast: true,
        //         position: 'top-end',
        //         showConfirmButton: false,
        //         timer: 5000,
        //         timerProgressBar: true,
        //         didOpen: (toast) => {
        //             toast.addEventListener('mouseenter', Swal.stopTimer)
        //             toast.addEventListener('mouseleave', Swal.resumeTimer)
        //         }
        //     });
        //     Toast.fire({
        //         icon: icon,
        //         title: message,
        //     });
        // }
        // // Inicializar a verificação de notificações ao carregar a página
        // $(document).ready(function() {
        //     checkNotifications();
        // });
    </script>

    @if (session('success'))
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Toast.fire({
                icon: 'success',
                title: "{!! session('success') !!}",
            })
        </script>
    @endif
    @if (session('error'))
        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Toast.fire({
                icon: 'error',
                title: "{!! session('error') !!}",
            })
        </script>
    @endif
</body>

</html>
