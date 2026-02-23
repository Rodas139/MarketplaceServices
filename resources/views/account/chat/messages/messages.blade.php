{{ Form::model($order, $formOptions) }}
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">
        <span class="fs-15" aria-hidden="true"><i class="fas fa-times"></i></span>
        <span class="sr-only">{{trans('account/orders.close') }}</span>
    </button>
    
    @if (empty($order->chat->closed_in))
      {{-- <div class="btn-group btn-table-actions" style="float: right" >
        <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
              aria-expanded="false">
              <span style="color: white">Ações</span> <i class="fas fa-ellipsis-v" style="color: white"></i>
          </button>
          <ul class="dropdown-menu pull-right">
              <li>
                  <a href="{{ route('admin.chat.close', $order->chat->id) }}" data-method="post"
                      data-confirm-title="Encerrar conversa" data-confirm-class="btn-success"
                      data-confirm-label="Encerrar"
                      data-confirm="Pretende encerrar o chat com o cliente?
                      <br><small><b>Nota:</b> Após encerrar não poderá abrir novamente um chat para esta encomenda.</small>" class="text-red">
                      <i class="fas fa-window-close"></i></i> Encerrar Chat
                  </a>
              </li>
          </ul>
      </div> --}}
    @endif

    <h4 class="modal-title">{{ $action }} @if (!empty($order->chat->closed_in)) <span style="color: red; font-weight: bold"> ({{trans('account/orders.chat-closed')}})</span>@endif</h4>
</div>

<div class="modal-body ">
    <div class="row row-5 chat-container" style="min-height: 400px ; max-height: 400px ; overflow: auto">
            @foreach ($order->chat->messages as $message)
                @if($message->message_customer)
                    <div class="box-message" style="margin-right: 40%">
                        <img src="{{ asset('assets/img/c.png') }}" alt="Avatar">
                        <p>{{$message->message}}
                          @if($message->file_name)
                            <br>
                            <a target="_blank" href="{{ route('chatMessage.file', $message->file_public_path) }}">{{$message->file_name}}</a> 
                          @endif
                        </p>
                        <span class="time-right">{{$message->date}}</span>
                    </div>
                @else
                    <div class="box-message darker" style="margin-left: 40%">
                        <img src="{{ asset('assets/img/m.png') }}" alt="Avatar" class="right">
                        <p>{{$message->message}}
                            @if($message->file_name)
                              <br>
                              <a target="_blank" href="{{ route('chatMessage.file', $message->file_public_path) }}">{{$message->file_name}}</a> 
                            @endif
                        </p>
                        
                        <span class="time-left">{{$message->date}}</span>
                    </div>
                @endif
            @endforeach
    </div>

    <a id="scrollBottomBtn" class="btn btn-secondary rounded-circle" style="display: none; background-color: blue;border-radius: 50% !important">
        <i class="fas fa-arrow-down fa-lg" style="color: white;"></i>
      </a>

    <div class="spacer-5"></div>
    <hr>

    @if (empty($order->chat->closed_in))
        <div class="row row-5" style="margin-top:20px">
            <div class="form-group">
                    {{ Form::textarea('message', null, ['class' => 'form-control text-message', 'style' => 'border-radius:5px', 'placeholder' => trans('account/orders.type-message'), 'rows' => 2]) }}
            </div>
            <div class="row row-5">
                <div class="col-md-8">
                  <div class="form-group m-b-5">
                    <p style="font-weight: bold">{{trans('account/orders.upload-artwork')}}</p>
                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                        <div class="form-control" data-trigger="fileinput">
                          <i class="fas fa-file fileinput-exists"></i>
                          <span class="fileinput-filename">{{trans('account/orders.select-attachment')}}</span>
                        </div>
                        <span class="input-group-addon btn btn-default btn-file">
                            <span class="fileinput-new">{{trans('account/orders.select')}}</span>
                            <span class="fileinput-exists">{{trans('account/orders.change')}}</span>
                            <input type="file" name="file" data-file-format="csv,xls,xlsx" >
                        </span>
                        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">{{trans('account/orders.annul')}}</a>
                    </div>
                  </div>  
                </div>
              <div class="col-md-4">
                <br>
                    <button class="btn btn-send" style=" border-radius: 5px; float: right; border-radius: 5px; margin-top:5px"  type="submit"> {{trans('account/orders.to-send')}} <i class="fas fa-paper-plane"></i></button>
              </div>
            </div>
        </div>
    @endif
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('account/orders.close')}}</button>
    {{-- <button id="xpto" type="submit" class="btn btn-primary">Gravar</button> --}}
</div>

{{ Form::close() }}




  <style>
    /* Chat containers */
.box-message {
  background-color: #e7e7e7;
  border-radius: 5px;
  padding: 15px;
  margin: 10px 0;

}

/* Darker chat container */
.darker {
  /* border-color: #adf1fd; */
  
  background-color: rgb(0 94 163 / 20%);
}

/* Clear floats */
.box-message::after {
  content: "";
  clear: both;
  display: table;
}

/* Style images */
.box-message img {
  float: left;
  max-width: 60px;
  width: 100%;
  margin-right: 20px;
  border-radius: 50%;
}

/* Style the right image */
.box-message img.right {
  float: right;
  margin-left: 20px;
  margin-right:0;
}

/* Style time text */
.time-right {
  float: right;
  color: #aaa;
}

/* Style time text */
.time-left {
  float: left;
  color: #999;
}

.modal-menu {
  position: relative;
  display: flex;
}

.dropdown {
  position: absolute;
  top: 50%;
  right: 15px;
  transform: translateY(-50%);
}

.dropdown-toggle {
  background-color: transparent;
  border: none;
  padding: 0;
  margin: 0;
}

.dropdown-item {
  cursor: pointer;
}
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#messageForm').submit(function(event) {
            event.preventDefault(); // Evita que o formulário seja enviado normalmente

            var submitButton = $(this).find('button[type="submit"]');
            var originalButtonHTML = submitButton.html(); // Armazena o HTML original do botão

            submitButton.html('<i class="fas fa-spinner fa-spin"></i> Enviando...'); 


            var url = $(this).attr('action');
            var formData = new FormData(this); // Cria um objeto FormData com os dados do formulário
            
            var fileInput = $(this).find('input[name="file"]');
            var message = $(this).find('textarea[name="message"]').val().trim();

            if (fileInput[0].files.length > 0) {

              var fileSize = fileInput[0].files[0].size; // Tamanho do arquivo em bytes
              var maxSize = 40 * 1024 * 1024; // 40 MB em bytes

              var fileName = fileInput[0].files[0].name;
              var fileExtension = fileName.split('.').pop().toLowerCase();

              if (fileExtension == 'php') {
                submitButton.html(originalButtonHTML);
                Growl.error('Não é possível enviar ficheiros com a extensão PHP');
                return;
              }

              if (fileSize > maxSize) {
                submitButton.html(originalButtonHTML);
                Growl.error('O tamanho máximo de upload permitido é de 40mb');
                return;
              }

            }else{
                if (message === '') {
                  submitButton.html(originalButtonHTML);
                Growl.error('Não é possível enviar mensagens em branco');
                return;
              }
            }

            $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false, // Não processe os dados
            contentType: false, // Não defina o cabeçalho de conteúdo
            success: function(response) {
                var newMessage = '<div class="box-message" style="margin-right: 40%">' +
                '<img src="{{ asset('assets/img/c.png') }}" alt="Avatar">' +
                '<p>' + response.message + '</p>' +
                '<span class="time-right">' + response.date + '</span>' +
                '</div>';

                $('.chat-container').append(newMessage);
                $('.text-message').val('');

                if(response.emailResponse == 0){
                  Growl.warning('Não foi possível notificar o cliente por email. No entanto a mensagem foi enviada.');
                }else{
                  Growl.success('Notificação por email enviada com sucesso.');

                }

                submitButton.html(originalButtonHTML);

                // Rolar para o fim da div
                var chatContainer = $('.chat-container');
                var scrollHeight = chatContainer.prop('scrollHeight');
                chatContainer.scrollTop(scrollHeight);
            },
            error: function(xhr, status, error) {
                // Lógica para lidar com erros da requisição AJAX
            }
            });
        });
    });

  var chatContainer = $('.chat-container');
  var scrollBottomBtn = $('#scrollBottomBtn');

  // Verificar a posição de rolagem ao carregar a página
  checkScrollPosition();

  // Verificar a posição de rolagem ao rolar a div
  chatContainer.scroll(function() {
    checkScrollPosition();
  });

  // Função para verificar a posição de rolagem e mostrar ou ocultar o botão
  function checkScrollPosition() {
    var scrollTop = chatContainer.scrollTop();
    var scrollHeight = chatContainer.prop('scrollHeight');
    var containerHeight = chatContainer.innerHeight();
    var scrollBottomThreshold = 20; // Defina a margem inferior para exibir o botão

    if (scrollTop + containerHeight < scrollHeight - scrollBottomThreshold) {
      // Ainda não está no fim do scroll
      scrollBottomBtn.show();
    } else {
      // Está no fim do scroll
      scrollBottomBtn.hide();
    }
  }

  // Rolagem para o fim do scroll ao clicar no botão
  scrollBottomBtn.click(function() {
    chatContainer.animate({ scrollTop: chatContainer.prop('scrollHeight') }, 'fast');
  });

  $('#scrollBottomBtn').click();

</script>