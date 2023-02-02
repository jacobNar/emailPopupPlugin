<?php
/*
Plugin Name: Email Popup
Plugin URI: http://www.example.com/email-popup
Description: A plugin that displays an email popup with a form field for the user's email address and a submit button.
Author: Your Name
Version: 1.0
Author URI: http://www.example.com
*/

// Check if we are on the front-end of the website
if (!is_admin()) {
  // Add the email popup HTML to the footer
  add_action('wp_footer', 'email_popup_html');
  function email_popup_html() {
    ?>
    <div id="email-popup-wrap" class="email-popup-wrap">
      <div id="email-popup" class="email-popup">
        <button id="close-popup" class="close-popup" onclick="closePopup()">x</button>
        <form action=”<?php echo htmlspecialchars($_SERVER[‘PHP_SELF’]); ?>” id="email-form">
          <h1 id='email-popup-title'>Get 10% off your order!</h1>
          <p id='email-popup-paragraph'>Join our mailing list for a 10% coupon delivered immediately after signup!</p>
          <label id='label-email'for="email" style="display:none">Enter your email address:</label><br>
          <input type="email" id="email" name="email"><br<br>
          <input class='email-popup-button' id='submit-button' name='submit' type="submit" value="submit">
        </form> 
        <!-- wp:block {"ref":458} /-->
      </div>
    </div>
    <style>
      .email-popup-wrap {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0, 0.5);
        z-index: 9999;
      }
      .email-popup {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        min-width: 40%;
        min-height: 40%;
      }
      
      .email-popup form {
          text-align: center;
      }
      
      .email-popup form h1 {
          margin: 15px 0;
      }
      .email-popup form p {
          margin: 0 0 15px 0;
      }
      
      .email-popup-button {
          margin-top: 10px;
      }
     
    </style>
    <script>
      window.onload = function() {
          if (!sessionStorage.getItem('popupShown')) {
            // Show the popup
            setTimeout(()=>{document.getElementById('email-popup-wrap').style.display = 'block';}, 10000)
            // Set the cookie so that the popup does not show up again
            sessionStorage.setItem('popupShown', 'true');
          }

            
            
            
            document.getElementById('email-form').addEventListener('submit', async function(event) {
            event.preventDefault();
            const email = document.getElementById('email').value;
            
            try {
                    var request = {
                    method: 'POST',
                    body: JSON.stringify({'email': email}),
                    headers: { 'Content-Type': 'application/json' }
                }
                const response = fetch('https://newvintageapparel.com/wp-content/plugins/email-popup/email-submit-alt.php', request).then((response) => {
                    debugger;
                    console.log(response)
                    if (!response.ok) {
                        console.log("error" + response)
                    }else {
                        var json = response.json();
                        console.log(json)
                        // document.getElementById('email-popup-wrap').style.display = 'none';
                        document.getElementById('email-popup-title').innerHTML = "Thank You!";
                        document.getElementById('email-popup-paragraph').innerHTML = "";
                        document.getElementById('email').style.display = 'none';
                        document.getElementById('label-email').style.display = 'none';
                        document.getElementById('submit-button').style.display = 'none';
                    }
                });

                
            } catch (error) {
                console.error(`There was a problem submitting the form: ${error}`);
                document.getElementById('email-popup-title').innerHTML = "Thank You!";
                document.getElementById('email-popup-paragraph').innerHTML = "";
                document.getElementById('email').style.display = 'none';
                document.getElementById('label-email').style.display = 'none';
                document.getElementById('submit-button').style.display = 'none';
            }
            });
        }
      
      
      function closePopup () {
          document.getElementById('email-popup-wrap').style.display = 'none';
      }
      
    </script>
    <?php
  }
}
