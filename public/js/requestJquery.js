// REGISTRO ====================================

//VERIFICAÇÃO ANTES DO REGISTRO
$("#salvarVenda").click(function(){
	
	let items = JSON.parse(localStorage.getItem('sale_items')) || [];
	let parcels = JSON.parse(localStorage.getItem('parcels')) || [];
	
	if (!$("#client_id").val()) {
		alert('Selecione um cliente.');
        return;
	}

	if (items.length === 0) {
		alert('Adicione o item.');
	    return;
	}

	if (parcels.length === 0) {
		alert('Adicione as parcelas.');
	    return;
	}

	salvaVenda();
});

//SALVA A VENDA
function salvaVenda(){

	const items = JSON.parse(localStorage.getItem('sale_items'));
    const parcels = JSON.parse(localStorage.getItem('parcels'));

    // $.ajaxSetup({
	//     headers: {
	//         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	//     }
	// });

	// Mostra o spinner
	$('#spinnerSalvar').removeClass('d-none');
	$('#salvarVenda').prop('disabled', true);

	$.ajax({
        type: "POST",
        url: "/sales",
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            client_id: $("#client_id").val(),
            items: items,
            parcels: parcels,
            _token: $('meta[name="csrf-token"]').attr('content') // CSRF token
        },
        success: function (response) {

        	if (response.success) {
        		alert(response.message);

        		window.location.href = "/sales";

	            localStorage.removeItem('parcels');
	            localStorage.removeItem('sale_items');

	            // Remove o spinner
				$('#spinnerSalvar').addClass('d-none');
				$('#salvarVenda').prop('disabled', false);

        	}else{
            	alert(response.message);

            	// Remove o spinner
				$('#spinnerSalvar').addClass('d-none');
				$('#salvarVenda').prop('disabled', false);
        	}
        },
        error: function (xhr) {
            alert("Erro ao salvar a venda." + xhr.responseText);

            // Remove o spinner
			$('#spinnerSalvar').addClass('d-none');
			$('#salvarVenda').prop('disabled', false);
        }
    });
}

// EDIÇÃO ====================================

//VERIFICAÇÃO ANTES DE ATUALIZAR
$("#atualizaVenda").click(function(){
	
	let items = JSON.parse(localStorage.getItem('sale_items')) || [];
	let parcels = JSON.parse(localStorage.getItem('parcels')) || [];
	
	if (!$("#client_id").val()) {
		alert('Selecione um cliente.');
        return;
	}

	if (items.length === 0) {
		alert('Adicione o item.');
	    return;
	}

	if (parcels.length === 0) {
		alert('Adicione as parcelas.');
	    return;
	}

	atualizaVenda();
});

//ATUALIZA A VENDA
function atualizaVenda(){

	const saleId = $('#sale_id').val();
	const items = JSON.parse(localStorage.getItem('sale_items'));
    const parcels = JSON.parse(localStorage.getItem('parcels'));

    $.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});

	// Mostra o spinner
	$('#spinnerAtualizar').removeClass('d-none');
	$('#atualizaVenda').prop('disabled', true);

	$.ajax({
        type: "PUT",
        url: `/sales/${saleId}`,
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            client_id: $("#client_id").val(),
            items: items,
            parcels: parcels,
            _token: $('meta[name="csrf-token"]').attr('content') // CSRF token
        },
        success: function (response) {

        	if (response.success) {
        		alert(response.message);

        		window.location.href = "/sales";

	            localStorage.removeItem('parcels');
	            localStorage.removeItem('sale_items');

	            // Remove o spinner
				$('#spinnerAtualizar').addClass('d-none');
				$('#atualizaVenda').prop('disabled', false);

        	}else{
            	alert(response.message);

            	// Remove o spinner
				$('#spinnerAtualizar').addClass('d-none');
				$('#atualizaVenda').prop('disabled', false);
        	}
        },
        error: function (xhr) {
            alert("Erro ao atualizar a venda." + xhr.responseText);

            // Remove o spinner
			$('#spinnerAtualizar').addClass('d-none');
			$('#atualizaVenda').prop('disabled', false);
        }
    });
}