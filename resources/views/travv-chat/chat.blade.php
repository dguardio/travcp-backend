<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <!--[if gt IE 7]>      
  <script type="text/javascript">
  // Let the library know where WebSocketMain.swf is:
  window.WEB_SOCKET_SWF_LOCATION = "static/lib/WebSocketMain.swf";  
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xdomain/0.7.5/xdomain.js"></script>
  <script>
    xdomain.slaves({
      "https://api-us-1.sendbird.com": "/xdomain.html",
      "https://api-p.sendbird.com": "/xdomain.html"
    });
  </script>
  <script src="static/lib/swfobject.js"></script>
  <script src="static/lib/web_socket.js"></script>
  <script src="static/lib/moxie.js"></script>
  <script>moxie.core.utils.Env.swf_url = 'static/lib/Moxie.min.swf';</script>
  <![endif]-->

  <script src="{{ asset('static/js/jquery-1.11.3.min.js') }}"></script>
  <!-- <link rel="shortcut icon" href="https://s3.amazonaws.com/sendbird-static/favicon/favicon.ico" type="image/x-icon"> -->

  <link href='https://fonts.googleapis.com/css?family=Exo+2:400,900italic,900,800italic,800,700italic,700,600italic,600,500italic,500,400italic,300italic,200italic,200,100italic,100,300'
        rel='stylesheet' type='text/css'>
  <link href='https://fonts.googleapis.com/css?family=Lato:400,900italic,900,800italic,800,700italic,700,600italic,600,500italic,500,400italic,300italic,200italic,200,100italic,100,300'
        rel='stylesheet' type='text/css'>

  <link rel="stylesheet" href="{{ asset('static/bootstrap/bootstrap.min.css')}}">
  <link rel="stylesheet" href="{{ asset('static/css/sample-chat.css')}}">

  <title>Messages | Trav CP</title>
  <style>
    .right-section {
        width: 75%;
        height: 100vh;
        float: left;
    }
    .left-nav {
        width: 25%;
        height: 100vh;
        background: #F81894;
        padding: 15vh 0 0 0;
        border-right: 1px solid #F81894;
        float: left;
        text-align: center;
    }
    .left-nav-channel-title {
      color: #FFF;
    }
    .left-nav-user {
        width: 25%;
        height: 72px;
        position: absolute;
        bottom: 0;
        background: #555;
        font-family: 'Lato';
    }
    .modal-open-chat {
        display: none;
        width: 300px;
        height: 100vh;
        position: absolute;
        background: #fff;
        top: 0;
        left: 25%;
        z-index: 2;
    }
    .left-nav-button {
      margin: auto;
    }
    .modal-messaging {
        display: none;
        width: 25%;
        height: 100vh;
        position: absolute;
        background: #fff;
        top: 0;
        left: 25%;
        z-index: 2;
    }
    .modal-messaging-bottom__button {
      margin: auto;
    }
    .left-nav-channel-empty {
        width: 192px;
        height: 72px;
        padding: 18px 0 0 14px;
        margin-left: 14px;
        border: 1px solid #FFF;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        font-family: 'Lato';
        font-weight: 500;
        font-size: 14px;
        color: #FFF;
        line-height: 18px;
        text-align: left;
        margin: auto;
    }
    .left-nav-user-icon {
    }
    .left-nav-channel{
      color: #FFF;
      text-align: left;
      margin-left: 10px;
    }
    .left-nav-channel-lastmessage {
      color: #FFF;
    }
    .left-nav-channel-lastmessagetime {
      color: #FFF;
    }
    .left-nav-channel-section {
        height: calc(100% - 240px);
        margin-top: 18px;
        overflow-y: auto;
        overflow-x: hidden;
    }
</style>
</head>
<body>
  <div class="init-check"></div>

  <div class="sample-body">

    <!-- left nav -->
    <div class="left-nav">
      <a href="https://travvapp.herokuapp.com/" target="_blank"><img class="travv_logo" src="https://travvapp.herokuapp.com/img/travv_brand_logo_overlay.081fdd17.png"></a>
      <div class="left-nav-channel-select">
        <button type="button" class="left-nav-button left-nav-open" id="btn_open_chat">
          OPEN CHANNEL
          <div class="left-nav-button-guide"></div> <br>
        </button>
        <button type="button" class="left-nav-button left-nav-messaging" id="btn_messaging_chat">
          INVITE USER
          <div class="left-nav-button-guide"></div>
        </button>
      </div>

      <div class="left-nav-channel-section">
        <div class="left-nav-channel-title">OPEN CHAT</div>
        <div class="left-nav-channel-empty">Get started to select<br>a channel</div>
        <div id="open_channel_list"></div>

        <div class="left-nav-channel-title title-messaging">GROUP CHAT</div>
        <div id="messaging_channel_list"></div>
      </div>

      <div class="left-nav-user">
        <div class="left-nav-user left-nav-user-icon"></div>
        <div class="left-nav-user left-nav-login-user">
          <div class="left-nav-user left-nav-user-title">username</div>
          <div class="left-nav-user left-nav-user-nickname"></div>
        </div>
      </div>

    </div> <!-- // end left nav -->


    <!-- chat section -->
    <div class="right-section">
      <!-- modal-bg -->
      <div class="right-section__modal-bg"></div>

      <!-- top -->
      <div class="chat-top">
        <div class="chat-top__title"></div>
        <div class="chat-top-button">

          <div class="chat-top__button chat-top__button-invite">INVITE</div>
          <div class="modal-guide-user">
            user list
          </div>

          <div class="chat-top__button chat-top__button-member"></div>
          <div class="modal-guide-member">
            Current member list
          </div>

          <div class="chat-top__button chat-top__button-hide"></div>
          <div class="chat-top__button chat-top__button-leave"></div>
          <div class="modal-guide-leave">
            Leave
          </div>

        </div>
      </div>

      <!-- channel empty -->
           <div class="chat-empty">
        <div class="chat-empty chat-empty__tile">WELCOME TO TRAV CP CHAT</div>
        <div class="chat-empty chat-empty__icon"></div>
        <div class="chat-empty chat-empty__desc">
          Click on Invite User to invite Merchant to your Channel.<br>
          If you don't have a channel to participate,<br>
          go ahead and create your first channel now.
        </div>
      </div>


      <!-- chat -->
      <div class="chat">
        <div class="chat-canvas"></div>

        <div class="chat-input">
              <div id="container">
    </div>
          <label class="chat-input-file" for="chat_file_input">
            <input type="file" name="file" id="chat_file_input" style="display: none;">
          </label>  
          <!--[if gt IE 7]>
          <script>
             $('.chat-input-file').remove();
          </script>
          <a class="chat-input-file" id="chat_file_input2" href="javascript:;">
          </a>
          <![endif]-->
          <div class="chat-input-text">
            <textarea class="chat-input-text__field" placeholder="Write a chat" disabled="true" autofocus></textarea>
          </div>
        </div>
        <label class="chat-input-typing"></label>
      </div>

    </div> <!-- // end chat section -->

  </div>
  <!-----------------------
            modal
  ------------------------>
<!--   <div class="modal-guide-create">
    <div class="modal-guide-create__title">Create Chat</div>
    <div class="modal-guide-create__desc">
      Click on any button to create a new channel<br>
      and start sending your first message!
    </div>
    <button type="button" class="modal-guide-create__button" id="guide_create">GOT IT!</button>
  </div> -->

  <div class="modal-open-chat">
    <div class="modal-messaging-top">
      <label class="modal-messaging-top__title">Open Channel</label>
      <button class="modal-messaging-top__btn" id="btn_create_open_channel"></button>
    </div>
    <div class="modal-open-chat-list"></div>
    <div class="modal-open-chat-more">
      <div class="modal-open-chat-more__text">MORE<div class="modal-open-chat-more__icon"></div></div>
    </div>
  </div>

  <div class="modal-messaging">
    <div class="modal-messaging-top">
      <label class="modal-messaging-top__title">Group Channel</label>
      <label class="modal-messaging-top__desc">Member list shows people inside the application.</label>
    </div>
    <div class="modal-messaging-list">
      <div class="modal-messaging-list__item">Username1<div class="modal-messaging-list__icon"></div></div>
      <div class="modal-messaging-list__item">Username2<div class="modal-messaging-list__icon modal-messaging-list__icon--select"></div></div>

      <div class="modal-messaging-more">MORE<div class="modal-messaging-more__icon"></div></div>
    </div>
    <div class="modal-messaging-bottom">
      <button type="button" class="modal-messaging-bottom__button" onclick="startMessaging()">START MESSAGE</button>
    </div>
  </div>

  <div class="modal-member">
    <div class="modal-member-title">CURRENT MEMBER LIST</div>
    <div class="modal-member-list"></div>
  </div>

  <div class="modal-invite">
    <div class="modal-invite-title">USER LIST</div>
    <div class="modal-invite-top">
      <label class="modal-messaging-top__title modal-invite-top__title">Group Channel</label>
      <label class="modal-invite-top__desc">Member list shows people inside the application.</label>
    </div>
    <div class="modal-messaging-list modal-invite-list">

    </div>
    <div class="modal-invite-bottom">
      <button type="button" class="modal-invite-bottom__button" onclick="inviteMember()">INVITE</button>
    </div>
  </div>

  <div class="modal-leave-channel">
    <div class="modal-leave-channel-card">
      <div class="modal-leave-channel-title">Are you Sure?</div>
      <div class="modal-leave-channel-desc">Do you want to leave this channel?</div>
      <div class="modal-leave-channel-separator"></div>
      <div class="modal-leave-channel-bottom">
        <button type="button" class="modal-leave-channel-button modal-leave-channel-close">CANCEL</button>
        <button type="button" class="modal-leave-channel-button modal-leave-channel-submit">YES</button>
      </div>
    </div>
  </div>

  <div class="modal-hide-channel">
    <div class="modal-hide-channel-card">
      <div class="modal-hide-channel-title">Are you Sure?</div>
      <div class="modal-hide-channel-desc">Do you want to hide this channel?</div>
      <div class="modal-hide-channel-separator"></div>
      <div class="modal-hide-channel-bottom">
        <button type="button" class="modal-hide-channel-button modal-hide-channel-close">CANCEL</button>
        <button type="button" class="modal-hide-channel-button modal-hide-channel-submit">YES</button>
      </div>
    </div>
  </div>

  <div class="modal-confirm">
    <div class="modal-confirm-card">
      <div class="modal-confirm-title">Are you Sure?</div>
      <div class="modal-confirm-desc">Do you want to hide this channel?</div>
      <div class="modal-confirm-separator"></div>
      <div class="modal-confirm-bottom">
        <button type="button" class="modal-confirm-button modal-confirm-close">CANCEL</button>
        <button type="button" class="modal-confirm-button modal-confirm-submit">YES</button>
      </div>
    </div>
  </div>

  <div class="modal-input">
    <div class="modal-input-card">
      <div class="modal-input-title">Type info</div>
      <div class="modal-input-desc">Create Open Channel</div>
      <div class="modal-input-box">
        <input type="text" class="modal-input-box-elem" />
      </div>
      <div class="modal-input-separator"></div>
      <div class="modal-input-bottom">
        <button type="button" class="modal-input-button modal-input-close">CANCEL</button>
        <button type="button" class="modal-input-button modal-input-submit">CREATE</button>
      </div>
    </div>
  </div>
  <script src="{{ asset('static/lib/SendBird.min.js') }}"></script>
  <script src="{{ asset('static/js/util.js') }}"></script>
  <script src="{{ asset('static/js/chat.js') }}"></script>

</body>
</html>
