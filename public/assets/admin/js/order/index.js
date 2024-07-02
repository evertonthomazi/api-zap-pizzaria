var url = window.location.origin;

$('#table-order').DataTable({
    processing: true,
    serverSide: true,
    "ajax": {
        "url": url + "/pedidos/getOrders",
        "type": "GET"
    },
    "columns": [{
        "data": "id"
    },
    {
        "data": "customer.name"
    },
    {
        "data": "total_price"
    },
    {
        "data": "status.name",
    },
    {
        "data": "payment_method",
    },
    {
        "data": "display_data"
    },
    {
        "data": "total_price"
    }
    ],
    'columnDefs': [{
        targets: [2],
        className: 'dt-body-center'
    }],
    'rowCallback': function (row, data, index) {
        $('td:eq(1)', row).html('<label>' + data['customer'].name + ' / ' + data['customer'].phone + '</label>');
        $('td:eq(5)', row).html('<label>' + data['display_data'] + '</label>');
        $('td:eq(6)', row).html('<a href="javascript:;" data-toggle="modal" onClick="configModal(' + data["id"] + ')" data-target="#modalInfo" class="btn btn-sm btn-gray delete"><i class="fa fa-eye"></i></a>');
        // Adicionando botões para selecionar o status
        $('td:eq(3)', row).html('<div class="div-circulo"><select class="form-control status-select" data-order-id="' + data['id'] + '">' +
            '<option value="1" style="color: red;" ' + (data['status'].id == 1 ? 'selected' : '') + '>Pendente</option>' +
            '<option value="2" style="color: orange;" ' + (data['status'].id == 2 ? 'selected' : '') + '>Processando</option>' +
            '<option value="3" style="color: green;" ' + (data['status'].id == 3 ? 'selected' : '') + '>Completo</option>' +
            '<option value="4" style="color: grey;" ' + (data['status'].id == 4 ? 'selected' : '') + '>Cancelado</option>' +
            '<option value="5" style="color: blue;" ' + (data['status'].id == 5 ? 'selected' : '') + '>Saiu Para Entrega</option>' +
            '</select><span class="status-dot" style="background-color:' + getStatusColor(data['status'].id) + '"></span></div>');

        // Função para obter a cor do status
        function getStatusColor(statusId) {
            switch (statusId) {
                case 1:
                    return 'red';
                case 2:
                    return 'orange';
                case 3:
                    return 'green';
                case 4:
                    return 'grey';
                case 5:
                    return 'blue';
                default:
                    return 'black';
            }
        }

        // Evento de mudança para o select
        $('td:eq(3) select', row).on('change', function () {
            var selectedStatus = $(this).val();
            var statusColor;

            // Determinar a cor do status selecionado
            switch (selectedStatus) {
                case '1':
                    statusColor = 'red';
                    break;
                case '2':
                    statusColor = 'orange';
                    break;
                case '3':
                    statusColor = 'green';
                    break;
                case '4':
                    statusColor = 'grey';
                    break;
                case '5':
                    statusColor = 'blue';
                    break;
                default:
                    statusColor = 'black';
                    break;
            }

            // Atualizar a classe da bola de status para refletir a cor do status selecionado
            $(this).siblings('.status-dot').css('background-color', statusColor);

        });
    },
    "order": [[ 0, "desc" ]]
});

// Evento de mudança de status
$(document).on('change', '.status-select', function () {
    var orderId = $(this).data('order-id');
    var newStatus = $(this).val();

    // Verifica se o novo status é 'Saiu Para Entrega'
    if (newStatus === '5') {
        // Exibe o modal de confirmação
        $('#confirmModal').modal('show');

        // Define a ação a ser executada quando o usuário confirmar
        $('#confirmModal').on('click', '#confirmBtn', function () {
            // Fecha o modal de confirmação
            $('#confirmModal').modal('hide');

            // Requisição AJAX para atualizar o status do pedido
            $.ajax({
                url: '/pedidos/atualizar-status', // Substitua pela sua rota de atualização de status
                type: 'POST', // Use POST ou PATCH, dependendo da sua configuração
                data: {
                    orderId: orderId,
                    newStatus: newStatus
                },
                success: function (response) {

                    console.log('Pedido #response' + newStatus);
                },
                error: function (xhr, status, error) {
                    console.error('Erro ao atualizar status do pedido:', error);
                }
            });
        });
    } else {
        // Requisição AJAX para atualizar o status do pedido diretamente
        $.ajax({
            url: '/pedidos/atualizar-status', // Substitua pela sua rota de atualização de status
            type: 'POST', // Use POST ou PATCH, dependendo da sua configuração
            data: {
                orderId: orderId,
                newStatus: newStatus
            },
            success: function (response) {
                console.log('Pedido #' + orderId + ': Status alterado para ' + newStatus);
            },
            error: function (xhr, status, error) {
                console.error('Erro ao atualizar status do pedido:', error);
            }
        });
    }
});

function configModal(id) {
    const dados = {
        id: id
    };
    // Fazendo a requisição AJAX POST
    $.ajax({
        url: "pedidos/getOrder", // Substitua pela URL do seu servidor
        type: "GET",
        data: dados,
        success: function (responseJson) {
            const response = JSON.parse(responseJson);

            $('#customer-name').val(response.customer.name);
            $('#customer-phone').val(response.customer.phone);
            $("#customer-address").text(response.customer.location);
            // Ação a ser executada em caso de sucesso
            $("#resposta").html("Requisição bem-sucedida: " + response);

            $("#table-items").empty();
            response.items.forEach(function (item) {
                console.log(item);
                // Dados do novo item a serem adicionados
                const nomeItem = item.name;
                const valorItem = item.price;

                // Criação do novo elemento <tr> com as células <td> contendo os dados do item
                const novoItem = `
                <tr>
                    <td>${nomeItem}</td>
                    <td>${valorItem}</td>
                </tr>
                `;

                // Adiciona o novo item à tabela, no final do <tbody> com ID "table-items"
                $("#table-items").append(novoItem);
            });
        },
        error: function (xhr, status, error) {
            // Ação a ser executada em caso de erro
            $("#resposta").html("Erro na requisição: " + error);
        }
    });
}
