      <div id="fb-root"></div>
      <script src="http://connect.facebook.net/en_US/all.js">
      </script>
      <script>
         FB.init({ 
            appId:'187627851270954', cookie:true, 
            status:true, xfbml:true 
         });
      </script>
         <fb:registration
            fields="[{'name':'name'}, {'name':'email'},
            {'name':'favorite_car','description':'What is your favorite car?',
            'type':'text'}]" redirect-uri="URL_TO_LOAD_AFTER_REGISTRATION">
    </fb:registration>

