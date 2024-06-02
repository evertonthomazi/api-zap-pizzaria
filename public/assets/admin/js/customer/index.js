var url = window.location.origin;
$('#table-customer').DataTable({
    processing: true,
    serverSide: true,
    "ajax": {
        "url": url + "/clientes/getCustomers",
        "type": "GET"
    },
    "columns": [{
        "data": "name"
    },{
        "data": "phone"
    },
    {
        "data": "jid"
        },
    
    {
        "data": "jid"
    }
    ],
    'columnDefs': [
        {
            targets: [2],
            className: 'dt-body-center'
        }
    ],
    'rowCallback': function (row, data, index) {
        $('td:eq(2)', row).html(data['display_created_at']);
        var deleteButton = '<div class="btn-acoes" ><a href="javascript:;" data-toggle="modal" onClick="configModalDelete(' + data["id"] + ')" data-target="#modalDelete" class="btn btn-sm btn-danger delete"><i class="far fa-trash-alt"></i></a>';
        var editButton = '<a href="' + url + '/clientes/editar/' + data["id"] + '" class="btn btn-sm btn-info edit"><i class="far fa-edit"></i></a></div>';
        $('td:eq(3)', row).html(deleteButton + editButton);
    },
});

function configModalDelete(id) {
    // Defina o valor do input hidden com o ID do registro a ser excluído
    document.getElementById('id_survey_deleta').value = id;

    // Atualize a ação do formulário para incluir o ID correto
    var form = document.querySelector('#modalDelete form');
    form.action = form.action.replace('{id}', id);
}

