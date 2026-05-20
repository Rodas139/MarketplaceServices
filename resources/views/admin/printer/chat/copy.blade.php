<style>
    .tblabel {
        font-weight: bold;
        padding: 3px;
        font-size: 12px;
        border-radius: 4px;
        border: 0.5px solid #555;
    }

    .tbinput {
        border: 0.5px solid #555;
        padding: 5px 2px;
        font-size: 10px;
        border-radius: 4px;
    }
    .box-message {
  background-color: #e7e7e7;
  border-radius: 5px;
  padding: 15px;
  margin: 10px 0;

}

/* Darker chat container */
.darker {
  background-color: rgba(0, 94, 163, 0.2);
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
</style>

<div class="row"  style="padding-top: 5mm;">
    <div class="fs-12 lh-1-3 p-t-25 p-l-20" style="width: 100%; height: 16mm;">
        @foreach ($chat->messages as $message)
                @if($message->message_customer)
                    <div class="box-message" style="margin-right: 40%">
                        <img src="{{ asset('assets/img/c.png') }}" alt="Avatar" style="float: left; max-width: 60px; margin-right:20px; border-radius: 50%">
                        <p>{{$message->message}}</p>
                        <span class="time-right">{{$message->date}}</span>
                    </div>
                @else
                    <div class="box-message darker" style="margin-left: 40%">
                        <img src="{{ asset('assets/img/m.png') }}" alt="Avatar" style="float: right; max-width: 60px; margin-left:20px; border-radius: 50%">
                        <p>{{$message->message}}</p>
                        <span class="time-left">{{$message->date}}</span>
                    </div>
                @endif
            @endforeach
    </div>
</div>

