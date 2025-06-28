//EDITAR VENDA
document.addEventListener('DOMContentLoaded', function () {
    if (typeof window.saleItemsFromBackend !== 'undefined') {
        localStorage.setItem('sale_items', JSON.stringify(window.saleItemsFromBackend));
        mostraItens();
    }

    if (typeof window.parcelsFromBackend !== 'undefined') {
        localStorage.setItem('parcels', JSON.stringify(window.parcelsFromBackend));
        mostraParcelas();
    }
});

//VOLTAR PARA TELA VENDAS
$("#cancelarEditar").click(function(){
    localStorage.removeItem('parcels');
    localStorage.removeItem('sale_items');

    history.go(-1);
});

// =========================================================

//MOSTRA CLIENTE SELECIONADO PARA A VENDA
$('#client_id').on('change', function () {
    const $selectedOption = $(this).find('option:selected');

    if ($(this).val()) {
        $('#info-name').text($selectedOption.data('name'));
        $('#info-cpf').text($selectedOption.data('cpf'));
        $('#client-info').removeClass('hidden');
    } else {
        $('#client-info').addClass('hidden');
        $('#info-name').text('');
        $('#info-cpf').text('');
    }
});

//SUBTOTAL AO SELECIONAR PRODUTO
$("#products").on("change", function () {
    const price = parseFloat($("#products option:selected").attr("data-price")) || 0;
    const quantity = parseInt($("#quantity").val()) || 1;

    if ($(this).val()) {
        $("#price").val((price.toFixed(2)).replace('.', ','));
        $("#subtotal").val(((price * quantity).toFixed(2)).replace('.', ','));
    }else{
        $("#price").val('');
        $("#quantity").val(1)
        $("#subtotal").val('');
    }
});

//ALTERA VALOR PELA QUANTIDADE SELECIONADA
$("#quantity").on("change", function () {
    
    const price = parseFloat($("#products option:selected").attr("data-price")) || 0;
    const quantity = parseInt($("#quantity").val()) || 1;

    $("#subtotal").val(((price * quantity).toFixed(2)).replace('.', ','));
 
});

//LISTA OS ITENS
mostraItens();

//ADICIONA ITEM
$('#add-item').click(function () {

    const productSelect = $('#products');
    const selectedOption = productSelect.find('option:selected');
    const productId = selectedOption.val();
    const productName = selectedOption.text();
    const price = $("#price").val();
    const quantity = $('#quantity').val();
    const subtotal = $("#subtotal").val();

    if (!productId) {
        alert('Selecione um produto.');
        return;
    }

    if (quantity <= 0) {
        alert('Quantidade deve ser maior que zero.');
        return;
    }

    let items = JSON.parse(localStorage.getItem('sale_items')) || [];

    const alreadyExists = items.find(item => item.id === productId);
    if (alreadyExists) {
        alert('Este produto já foi adicionado!');
        return;
    }

    const newItem = {
        id: productId,
        name: productName,
        price: price,
        quantity: quantity,
        subtotal: subtotal
    };

    items.push(newItem);
    localStorage.setItem('sale_items', JSON.stringify(items));

    mostraItens();

    productSelect.val('');
    $('#quantity').val(1);
    $('#price').val('');
    $('#subtotal').val('');

    localStorage.removeItem('parcels');
    mostraParcelas();

    $('#payment_method').val('');
    $('#installments').val(1);
    $('#due_date').val('');
    $("#parcel_value").val('');
});

//REMOVE ITEM
$(document).on('click', '.remove-item', function () {
    const index = $(this).data('index');
    let items = JSON.parse(localStorage.getItem('sale_items')) || [];

    items.splice(index, 1);
    localStorage.setItem('sale_items', JSON.stringify(items));

    mostraItens();

    localStorage.removeItem('parcels');
    mostraParcelas();

    const dueDate = $('#due_date').val();

    $('#payment_method').val('');
    $('#installments').val(1);
    $('#due_date').val('');
    $("#parcel_value").val('');
});

//EDITAR ITEM
$(document).on('click', '.edit-item', function () {
    const index = $(this).data('index');
    const items = JSON.parse(localStorage.getItem('sale_items')) || [];
    const item = items[index];

    if (!item) return;

    $('#products').val(item.id).trigger('change');
    $('#quantity').val(item.quantity);
    $('#price').val(item.price);
    $('#subtotal').val(item.subtotal);

    items.splice(index, 1);
    localStorage.setItem('sale_items', JSON.stringify(items));
    mostraItens();
});

//MOSTRA OS ITENS
function mostraItens() {
    const tbody = $('#items-table tbody');
    tbody.empty();

    let items = JSON.parse(localStorage.getItem('sale_items')) || [];

    items.forEach((item, index) => {
        const row = `
            <tr>
                <td class="px-4 py-2">${index + 1}</td>
                <td class="px-4 py-2">${item.name}</td>
                <td class="px-4 py-2">${item.quantity}</td>
                <td class="px-4 py-2">R$ ${item.price}</td>
                <td class="px-4 py-2">R$ ${item.subtotal}</td>
                <td class="px-4 py-2">
                    <button type="button" class="edit-item btn btn-warning px-2 py-1 text-white rounded text-xs" data-index="${index}">Editar</button>
                    <button type="button" class="remove-item btn btn-danger px-2 py-1 text-white rounded text-xs" data-index="${index}">Remover</button>
                </td>
            </tr>
        `;
        tbody.append(row);
    });

    const total = calcularTotalItens();
    $("#valorTotal").text(`Valor Total: R$ ${total.toFixed(2).replace('.', ',')}`);
}

//MOSTRA VALOR DA PARCELA AO SELECIONAR FORMA DE PAGAMENTO
$('#payment_method').on('change', function () {
    const total = calcularTotalItens();
    const installments = parseInt($("#installments").val());

    if (installments > 0 && total > 0) {
        const value = (total / installments).toFixed(2);
        $('#parcel_value').val(`R$ ${value.replace('.', ',')}`);
    } else {
        $('#parcel_value').val('');
    }
});

//MOSTRA VALOR DA PARCELA QUANDO ALTERA QUANTIDADE DE PARCELAS
$('#installments').on('change', function () {
    const total = calcularTotalItens();
    const installments = parseInt($(this).val());

    if (installments > 0 && total > 0) {
        const value = (total / installments).toFixed(2);
        $('#parcel_value').val(`R$ ${value.replace('.', ',')}`);
    } else {
        $('#parcel_value').val('');
    }
});

//MOSTRAR PARCELAS
mostraParcelas();

//GERAR PARCELAS
$('#gerarParcelas').click(() => {
    const total = calcularTotalItens();
    const installments = parseInt($('#installments').val());
    const dueDate = $('#due_date').val();

    if (!installments || installments < 1 || installments > 12) {
        return alert('Número de parcelas inválido.');
    }
    if (!$('#payment_method').val()) {
        return alert('Selecione Forma de Pagamento.');
    }
    if (!dueDate) {
        return alert('Informe a data de vencimento.');
    }

    const valorParcela = parseFloat((total / installments).toFixed(2));
    let dataFormatar = fusoDate(dueDate);
    const parcelas = [];

    for (let i = 0; i < installments; i++) {
        parcelas.push({
            numero: i + 1,
            vencimento: formataDate(dataFormatar),
            valor: valorParcela
        });
        dataFormatar.setMonth(dataFormatar.getMonth() + 1);
    }

    //SALVA PARCELAS NO LOCALSTORAGE
    salvaParcelas(parcelas);
});

// EDITAR PARCELA
$(document).on('change', '.vencimento-input, .valor-input', function () {
    const parcelas = JSON.parse(localStorage.getItem('parcels')) || [];
    const $row = $(this).closest('tr');
    const index = $row.data('index');
    if (index !== 0) return;

    const novaData = $row.find('.vencimento-input').val();
    let novoValor = parseFloat($row.find('.valor-input').val().replace(',', '.'));
    const total = calcularTotalItens();

    if (!novaData || isNaN(novoValor)) return alert('Data ou valor inválido.');

    if (parcelas.length > 1 && novoValor >= total){ 
        mostraParcelas();
        return alert('Valor da primeira parcela não pode ser maior ou igual que o total.');
    }

    if (parcelas.length === 1 && novoValor !== total){ 
        mostraParcelas();
        return alert('Valor da parcela não pode ser maior ou menor que o total.');
    }

    const restantesQtd = parcelas.length - 1;
    const restante = total - novoValor;
    const valorRestante = restantesQtd > 0 ? parseFloat((restante / restantesQtd).toFixed(2)) : 0;

    let dataAtual = fusoDate(novaData);
    const novaLista = parcelas.map((p, i) => {
        const obj = {
            numero: i + 1,
            vencimento: formataDate(dataAtual),
            valor: i === 0 ? novoValor : valorRestante
        };
        dataAtual.setMonth(dataAtual.getMonth() + 1);
        return obj;
    });

    salvaParcelas(novaLista);
});


// REMOVE PARCELA, E AJUSTA VALORES ENTRE AS PARCELAS RESTANTES
$(document).on('click', '.delete-parcel', function () {
    let parcelas = JSON.parse(localStorage.getItem('parcels')) || [];
    const index = $(this).closest('tr').data('index');
    parcelas.splice(index, 1);

    if (parcelas.length === 0) {
        localStorage.removeItem('parcels');
        $('#parcels-table tbody').empty();
        return;
    }

    const total = calcularTotalItens();
    const qtd = parcelas.length;

    let valorBase = parseFloat((total / qtd).toFixed(2));

    let dataAtual = fusoDate(parcelas[0].vencimento);
    const novasParcelas = parcelas.map((_, i) => {
        const obj = {
            numero: i + 1,
            vencimento: formataDate(dataAtual),
            valor: valorBase
        };
        dataAtual.setMonth(dataAtual.getMonth() + 1);
        return obj;
    });

    let somaParcelas = 0;
    for (let i = 0; i < novasParcelas.length; i++) {
        somaParcelas += novasParcelas[i].valor;
    }

    let diferenca = parseFloat((total - somaParcelas).toFixed(2));
    if (diferenca !== 0) {
        novasParcelas[novasParcelas.length - 1].valor += diferenca;
    }

    salvaParcelas(novasParcelas);
});

//SALVA AS PARCELAS NO LOCALSTORAGE
function salvaParcelas(parcelas) {
    localStorage.setItem('parcels', JSON.stringify(parcelas));
    mostraParcelas();
}

// MOSTRA PARCELAS NA TABELA
function mostraParcelas() {
    const parcelas = JSON.parse(localStorage.getItem('parcels')) || [];
    const $tbody = $('#parcels-table tbody');
    $tbody.empty();

    parcelas.forEach(({numero, vencimento, valor}, i) => {
        const valorNumerico = parseFloat(String(valor).replace(',', '.'));
        const valorFormatado = isNaN(valorNumerico) ? '0,00' : valorNumerico.toFixed(2).replace('.', ',');

        $tbody.append(`
            <tr data-index="${i}">
                <td class="px-4 py-2">${numero}</td>
                <td class="px-4 py-2">
                    <input type="date" class="vencimento-input border rounded px-2 py-1" value="${vencimento}" ${i !== 0 ? 'disabled' : ''}>
                </td>

                <td class="px-4 py-2 flex">
                    <p style="margin-top:3%;">R$ </p>
                    <input style="margin-left:3%;" type="text" class="valor-input border rounded px-2 py-1" value="${valorFormatado}" ${i !== 0 ? 'readonly' : ''}>
                </td>
                <td class="px-4 py-2">
                    <button type="button" class="delete-parcel btn btn-danger px-2 py-1 text-white rounded text-xs">Excluir</button>
                </td>
            </tr>
        `);
    });
}


//CALCULA VALOR DOS ITENS
function calcularTotalItens() {
    const items = JSON.parse(localStorage.getItem('sale_items')) || [];
    let total = 0;

    for (let i = 0; i < items.length; i++) {
        let subTotal = items[i].subtotal;

        let novoSubtotal = parseFloat(subTotal.replace(',', '.'));

        if (!isNaN(novoSubtotal)) {
            total += novoSubtotal;
        }
    }

    return total;
}

//APENAS PARA CRIAR DATA SEM PROBLEMA DE FUSO
function fusoDate(date) {
    const [ano, mes, dia] = date.split('-').map(Number); // ["2025", "06", "23"]
    return new Date(ano, mes - 1, dia);
}

// FORMATA DATA PARA YYYY-MM-DD
function formataDate(date) {
    const dia = String(date.getDate()).padStart(2, '0'); // 03
    const mes = String(date.getMonth() + 1).padStart(2, '0'); // 06
    const ano = date.getFullYear();
    return `${ano}-${mes}-${dia}`; // "2025-06-23"
}
