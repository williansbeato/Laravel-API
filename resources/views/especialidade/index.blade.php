@extends('templates.main', ['titulo'=> "Especialidade",'tag' => "ESP"])

@section('conteudo')
<div class="row">
    <div class="col-sm-12">
        <button class="btn btn-primary btn-block" onClick="criar()">
           <b>Cadastrar Nova Especialidade</b>
        </button>     
    </div>
</div>
        
<x-especialidadeTableList :header="['Nome', 'Eventos']" :data="$especialidade"/>
     
<div class="modal fade" tabindex="-1" role="dialog" id="modalEspecialidade"> 
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="form-horizontal" id="formEspecialidade">
                <div class="modal-header">
                    <h5 class="modal-title"><b>Nova Especialidade</b></h5>
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
                            <label><b>Descrição</b></label>
                            <textarea type="text" class="form-control" name="descricao" id="descricao" required></textarea>

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
                <h5 class="modal-title"><b>Informações da Especialidade</b></h5>
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
                <h5 class="modal-title"><b>Remover Especialidade</b></h5>
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
            $('#modalEspicialidade').modal().find('.modal-title').text("Cadastrar Especialidade");
            $('#nome').val('');
            $('#descricao').val('');
            $('#modalEspecialidade').modal('show');
        }

        function editar(id){
            $('#modalEspecialidade').modal().find('.modal-title').text("Alterar Especialidade");

            $.getJSON('/api/especialidade/'+id, function(data){
               
                $('#id').val(data.id);
                $('#nome').val(data.nome);
                $('#descricao').val(data.descricao);
                $('#modalEspecialidade').modal('show');    
                
            });        
        }

        function remover(id, nome){
            $('#modalRemove').modal().find('.modal-title').text("");
            $('#modalRemove').modal().find('.modal-title').append("Deseja Remover a Especialidade '"+ nome +"'?");
            $('#id_remove').val(id);
            $('#modalRemove').modal('show');

        }

        function visualizar(id){
            $('#modalInfo').modal().find('.modal-body').html("");

            $.getJSON('/api/especialidade/'+id, function(data){

                $('#modalInfo').modal().find('.modal-body').append("<b>ID:</b> " + data.id + "<br></br>");
                $('#modalInfo').modal().find('.modal-body').append("<b>NOME:</b> " + data.nome + "<br></br>");
                $('#modalInfo').modal().find('.modal-body').append("<b>DESCRIÇÃO:</b> " + data.descricao + "<br></br>");

            });

        }

        $("#formEspecialidade").submit( function(event){

            event.preventDefault();

            if($("#id").val() !=''){
                update( $("#id").val() );

            }
            else{
                insert();
               
            }

            $("#modalEspecialidade").modal('hide');
        });

        function insert(){
            
            especialidade = {
                nome: $("#nome").val(),
                descricao: $("#descricao").val(),
                
            };

            $.post("/api/especialidade", especialidade, function(data){
                novoEspecialidade = JSON.parse(data);
                linha = getLin(novoEspecialidade);
                $("#tabela>tbody").append(linha)

            });
        }

        function update(id){
            especialidade = {
                nome : $("#nome").val(),
                descricao : $("#descricao").val(),
            }

            $.ajax({
                type: "PUT",
                url : "/api/especialidade/" + id,
                context : this,
                data : especialidade,
                success : function(data) {
                    linhas = $("#tabela>tbody>tr");

                    e = linhas.filter(function(i,e){
                        return e.cells[0].textContent == id;
                    });

                    if(e){
                        e[0].cells[1].textContent = especialidade.nome.toUpperCase();
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
                url : "/api/especialidade/" + id,
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

        function getLin(especialidade){
            
            var linha = 
            "<tr style='text-align: center'>" +
                "<td>" + especialidade.nome + "</td>" +
                "<td>" +   
                    "<a nohref style='cursor:pointer' onCLick='visualizar("+ especialidade.id +")'><i class='fa fa-info'></i></a>" +              
                    "<a nohref style='cursor:pointer' onCLick='editar("+ especialidade.id +")'><i class='fa fa-pencil'></i></a>" + 
                    "<a nohref style='cursor:pointer' onCLick='remover("+ especialidade.id +")'><i class='fa fa-times'></i></a>" +
                "</td>" +
            "</tr>";

            return linha;

        }


    </script>

@endsection