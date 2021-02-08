@extends('templates.main', ['titulo'=> "Veterinario",'tag' => "VET"])

@section('conteudo')
<div class="row">
    <div class="col-sm-12">
        <button class="btn btn-primary btn-block" onClick="criar()">
           <b>Cadastrar Novo Veterinario</b>
        </button>     
    </div>
</div>
        
<x-veterinarioTableList :header="['Nome','Eventos']" :data="$veterinario"/>

<div class="modal fade" tabindex="-1" role="dialog" id="modalVeterinario"> 
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="form-horizontal" id="formVeterinario">
                <div class="modal-header">
                    <h5 class="modal-title"><b>Novo Veterinario</b></h5>
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
                            <label><b>CRMV</b></label>
                            <input type="text" class="form-control" name="crmv" id="crmv" required>
                        </div>
                    </div>

                    <div class="row" style="margin-top:10px">
                        <div class="col-sm-12">
                            <label><b>Especialidade</b></label>
                            <select class="form-control" name="especialidade" id="especialidade" required>
                                @foreach ( $especialidade ?? [] as $item)
                                    <option value="{{ $item['id'] }}"><p> {{ $item['nome']}} </p></option>
                                @endforeach
                            </select>
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
                <h5 class="modal-title"><b>Informações do Veterinario</b></h5>
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
                <h5 class="modal-title"><b>Remover Veterinario</b></h5>
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
            $('#especialidade').removeAttr('selected');
            $('#modalVeterinario').modal().find('.modal-title').text("Cadastrar Veterinario");
            $('#nome').val('');
            $('#crmv').val('');
            $('#modalVeterinario').modal('show');
        }

        function editar(id){
            $('#modalVeterinario').modal().find('.modal-title').text("Alterar Veterinario");

            $.getJSON('/api/veterinario/'+id, function(data){
               
                $('#especialidades option[value="${data.especialidade_id}"]').attr('selected');

                $('#id').val(data.id);
                $('#nome').val(data.nome);
                $('#crmv').val(data.crmv);
                $('#especialidade').val(data.especialidade_id);
                $('#modalVeterinario').modal('show');    
                
            });        
        }

        function remover(id, nome){
            $('#modalRemove').modal().find('.modal-title').text("");
            $('#modalRemove').modal().find('.modal-title').append("Deseja Remover o Veterinario '"+ nome +"'?");
            $('#id_remove').val(id);
            $('#modalRemove').modal('show');

        }

        function visualizar(id){
            $('#modalInfo').modal().find('.modal-body').html("");

            $.getJSON('/api/veterinario/'+id, function(data){

                $('#modalInfo').modal().find('.modal-body').append("<b>ID:</b> " + data.id + "<br></br>");
                $('#modalInfo').modal().find('.modal-body').append("<b>NOME:</b> " + data.nome + "<br></br>");
                $('#modalInfo').modal().find('.modal-body').append("<b>CRMV:</b> " + data.crmv + "<br></br>");
                $("#modalInfo").modal().find('.modal-body').append("<b>ESPECIALIDADE:</b> " + data.especialidade.nome + "<br><br>");

            });

        }

        $("#formVeterinario").submit( function(event){

            event.preventDefault();

            if($("#id").val() !=''){
                update( $("#id").val() );

            }
            else{
                insert();
               
            }

            $("#modalVeterinario").modal('hide');
        });

        function insert(){
            
            veterinario = {
                nome: $("#nome").val(),
                crmv: $("#crmv").val(),                
                especialidade: $("#especialidade").val()

            };

            $.post("/api/veterinario", veterinario, function(data){
                novoVeterinario = JSON.parse(data);
                linha = getLin(novoVeterinario);
                $("#tabela>tbody").append(linha)

            });
        }

        function update(id){
            veterinario = {
                nome : $("#nome").val(),
                crmv : $("#crmv").val(),
                especialidade: $("#especialidade").val(),

            }

            $.ajax({
                type: "PUT",
                url : "/api/veterinario/" + id,
                context : this,
                data : veterinario,
                success : function(data) {
                    linhas = $("#tabela>tbody>tr");

                    e = linhas.filter(function(i,e){
                        return e.cells[0].textContent == id;
                    });

                    if(e){
                        e[0].cells[1].textContent = veterinario.nome.toUpperCase();
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
                url : "/api/veterinario/" + id,
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

        function getLin(veterinario){
            
            var linha = 
            "<tr style='text-align: center'>" +
                "<td>" + veterinario.nome + "</td>" +
                "<td>" +   
                    "<a nohref style='cursor:pointer' onCLick='visualizar("+ veterinario.id +")'><i class='fa fa-info'></i></a>" +              
                    "<a nohref style='cursor:pointer' onCLick='editar("+ veterinario.id +")'><i class='fa fa-pencil'></i></a>" + 
                    "<a nohref style='cursor:pointer' onCLick='remover("+ veterinario.id +")'><i class='fa fa-times'></i></a>" +
                "</td>" +
            "</tr>";

            return linha;

        }

    </script>
        
@endsection