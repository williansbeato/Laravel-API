@extends('templates.main', ['titulo'=> "Cliente",'tag' => "CLI"])

@section('conteudo')
<div class="row">
    <div class="col-sm-12">
        <button class="btn btn-primary btn-block" onClick="criar()">
           <b>Cadastrar Novo Cliente</b>
        </button>        
    </div>
</div>
        
<x-clienteTableList :header="['Nome', 'Eventos']" :data="$cliente"/>


<div class="modal fade" tabindex="-1" role="dialog" id="modalCliente"> 
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="form-horizontal" id="formCliente">
                <div class="modal-header">
                    <h5 class="modal-title"><b>Novo Cliente</b></h5>
                </div>

                <div class="modal-body">

                    <input type="hidden" class="form-control" id="id">
                    <div class="row">
                        <div class="col-sm-12">
                            <label><b>Nome</b></label>
                            <input type="text" class="form-control" name="nome" id="nome" required>
                        </div>
                    </div>

                    <div class="row" style="margin-top:10px">
                        <div class="col-sm-12">
                            <label><b>E-mail</b></label>
                            <input type="email" class="form-control" name="email" id="email" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12" style="margin-top:10px">
                            <label><b>Telefone</b></label>
                            <input type="text" class="form-control" name="telefone" id="telefone" required>
                        </div>
                    </div>
                
                </div>

                <div class="modal-footer">
                    
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <button type="cancel" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>

                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" tabindex="-1" role="dialog" id="modalInfo"> 
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Informações do Cliente</b></h5>
            </div>

            <div class="modal-body">

            </div>

            <div class="modal-footer">       
                <button type="cancel" class="btn btn-success" data-dismiss="modal"><b>OK</b></button>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" tabindex="-1" role="dialog" id="modalRemove"> 
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <input type="hidden" id="id_remove" class="form-control">
            <div class="modal-header">
                <h5 class="modal-title"><b>Remover Cliente</b></h5>
            </div>

            <div class="modal-body">

            </div>

            <div class="modal-footer">    
                <button class="btn btn-danger" onClick="remove()"><b>Remover</b></button>
                <button type="cancel" class="btn btn-secondary" data-dismiss="modal"><b>Cancelar</b></button>

            </div>
        </div>
    </div>
</div>
        
@endsection


@section('script')

    <script type="text/javascript">

        $.ajaxSetup({

            headers: {

                'X-CSRF-TOKEN' : "{{csrf_token()}}"
            }
        });

        function criar(){
            $('#modalCliente').modal().find('.modal-title').text("Cadastrar Cliente");
            $('#nome').val('');
            $('#email').val('');
            $('#telefone').val('');
            $('#modalCliente').modal('show');
        }

        function editar(id){
            $('#modalCliente').modal().find('.modal-title').text("Alterar Cliente");

            $.getJSON('/api/cliente/'+id, function(data){
               
                $('#id').val(data.id);
                $('#nome').val(data.nome);
                $('#email').val(data.email);
                $('#telefone').val(data.telefone);
                $('#modalCliente').modal('show');    
                
            });        
        }

        function remover(id, nome){
            $('#modalRemove').modal().find('.modal-title').text("");
            $('#modalRemove').modal().find('.modal-title').append("Deseja Remover o Cliente '"+ nome +"'?");
            $('#id_remove').val(id);
            $('#modalRemove').modal('show');

        }

        function visualizar(id){

            $('#modalInfo').modal().find('.modal-body').html("");
            
            $.getJSON('/api/cliente/'+id, function(data){

                

                $('#modalInfo').modal().find('.modal-body').append("<b>ID:</b> " + data.id + "<br></br>");
                $('#modalInfo').modal().find('.modal-body').append("<b>NOME:</b> " + data.nome + "<br></br>");
                $('#modalInfo').modal().find('.modal-body').append("<b>E-MAIL:</b> " + data.email + "<br></br>");
                $('#modalInfo').modal().find('.modal-body').append("<b>TELEFONE:</b> " + data.telefone + "<br></br>");
                $('#modalInfo').modal('show');

            });

        }

        $("#formCliente").submit( function(event){

            event.preventDefault();

            if($("#id").val() !=''){
                update( $("#id").val() );

            }
            else{
                insert();
               
            }

            $("#modalCliente").modal('hide');
        });

        function insert(){
            
            cliente = {
                nome: $("#nome").val(),
                email: $("#email").val(),
                telefone: $("#telefone").val(),

            };

            $.post("/api/cliente", cliente, function(data){
                novoCliente = JSON.parse(data);
                linha = getLin(novoCliente);
                $("#tabela>tbody").append(linha)

            });
        }

        function update(id){
            cliente = {
                nome : $("#nome").val(),
                email : $("#email").val(),
                telefone : $("#telefone").val(),
            }

            $.ajax({
                type: "PUT",
                url : "/api/cliente/" + id,
                context : this,
                data : cliente,
                success : function(data) {
                    linhas = $("#tabela>tbody>tr");

                    e = linhas.filter(function(i,e){
                        return e.cells[0].textContent == id;
                    });

                    if(e){
                        e[0].cells[1].textContent = cliente.nome.toUpperCase();
                    }
                },
                error : function(error){
                    alert('ERRO - UPDATE');
                }
            });
        }

        function remove(){
            var id = $("#id_remove").val();

            $.ajax({
                type: "DELETE",
                url : "/api/cliente/" + id,
                context : this,
                success : function() {
                    linhas = $("#tabela>tbody>tr");

                    e = linhas.filter(function(i,e){
                        return e.cells[0].textContent == id;
                    });

                    if(e){
                        e.remove();
                    }
                },
                error : function(error){
                    alert('ERRO - DELETE');
                }
            });

            $('#modalRemove').modal('hide');

        }

        function getLin(cliente){
            
            var linha = 
            "<tr style='text-align: center'>" +
                "<td>" + cliente.nome + "</td>" +
                "<td>" +   
                    "<a nohref style='cursor:pointer' onCLick='visualizar("+ cliente.id +")'><i class='fa fa-info'></i></a>" +              
                    "<a nohref style='cursor:pointer' onCLick='editar("+ cliente.id +")'><i class='fa fa-pencil'></i></a>" + 
                    "<a nohref style='cursor:pointer' onCLick='remover("+ cliente.id +")'><i class='fa fa-times'></i></a>" +
                "</td>" +
            "</tr>";

            return linha;

        }

    </script>

@endsection
