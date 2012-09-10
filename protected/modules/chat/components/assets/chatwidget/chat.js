/**
 * Author: ALEX
 * Email: alexvnsky@gmail.com
 * Date: 09.09.12
 */

var chat = {
    /**
     * @var string CSRF token for CSRF validation.
     */
    csrfToken : '',

    /**
     * @var string CSRF token name for CSRF validation.
     */
    csrfTokenName : null,

    /**
     * @var int The timeout for callback function in milliseconds.
     */
    timeout: 5000,

    /**
     * @var string getMessages Action URL
     */
    getMessagesUrl: null,

    /**
     * @var string addMessage Action URL
     */
    addMessageUrl: null,

    /**
     * JScrollPanel
     */
    apiJsp: null,

    /**
     * @var bool register JScrollPane - default true
     */
    registerJScrollPane: true,

    /**
     * Add Message function.
     */
    addMessage : function(){
        // Make an AJAX call to get chat messages
        $.ajax({
            type: "POST",
            url: this.addMessageUrl,
            data: this.csrfToken + '&form=' + $("#chat-form").serialize(),
            dataType : "json",
            success:function(data){
                if (data.status == "success"){
                    chat.getMessages();
                } else
                    alert(data.content)
            }
        });
    },


    /**
     * Add loading indicator for feedback.
     */
    addLoader : function(){
        $('#chat-loader').show();
    },

    /**
     * Remove loading indicator.
     */
    removeLoader : function(){
        $('#chat-loader').hide();
    },

    /**
     * Get Messages function.
     */
    getMessages : function(){
        $.ajax({
            type: 'POST',
            url: this.getMessagesUrl,
            data: this.csrfToken,
            dataType : 'json',
            beforeSend: function(){
                chat.addLoader();
            },
            complete: function(){
                chat.removeLoader();
            },
            success:function(data){
                if (data.status == 'success'){
                    if(chat.registerJScrollPane){
                        chat.apiJsp.getContentPane().html(data.content);
                        chat.apiJsp.reinitialise();
                        chat.apiJsp.scrollToBottom();
                    } else
                        $('#chat-data').html(data.content);

                } else
                    $('#chat-data').html('Error loading chat');
            }
        });
    },

    /**
     * Get CSRF token for CSRF validation.
     * @return string CSRF token if CSRF validation is enabled.
     */
    getCsrfToken : function(){
        if( ( jQuery.cookie ) && ( this.csrfTokenName != null ) )
        {
            return ( '&' + this.csrfTokenName + '=' + $.cookie( this.csrfTokenName ) );
        }
    },

    /**
     * Initialize Chat.
     */
    init : function(){
        // Set CSRF token
        this.csrfToken = this.getCsrfToken();

        //JScrollPanel
        var element = $('#chat-content').jScrollPane();
        this.apiJsp = element.data('jsp');

    }

};