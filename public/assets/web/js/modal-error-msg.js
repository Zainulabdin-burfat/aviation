  document.getElementById('modal-form').addEventListener('submit', function(event) {
              // Prevent the default form submission behavior
              event.preventDefault();
            
              // Check each input field for errors
              const nameInput = document.getElementById('modal_fname');
              const emailInput = document.getElementById('modal_email');
              const numberInput = document.getElementById('modal_number');
              const service = document.getElementById('modal_service');
              const message = document.getElementById('modal_msg');
              let formValid = true;
            
              if (nameInput.value.trim() === '') {
                document.getElementById('modal_error-name').style.display = 'block';
                formValid = false;
              } else {
                document.getElementById('modal_error-name').style.display = 'none';
              }
            
              if (emailInput.value.trim() === '') {
                document.getElementById('modal_error-email').style.display = 'block';
                formValid = false;
              } else {
                document.getElementById('modal_error-email').style.display = 'none';
              }
              if (nameInput.value.trim() === '') {
                document.getElementById('modal_error-number').style.display = 'block';
                formValid = false;
              } else {
                document.getElementById('modal_error-number').style.display = 'none';
              }
              if (nameInput.value.trim() === '') {
                document.getElementById('modal_error-service').style.display = 'block';
                formValid = false;
              } else {
                document.getElementById('modal_error-service').style.display = 'none';
              }
              if (nameInput.value.trim() === '') {
                document.getElementById('modal_error-msg').style.display = 'block';
                formValid = false;
              } else {
                document.getElementById('modal_error-msg').style.display = 'none';
              }
              // If there are errors, do not proceed with the form submission
              if (!formValid) {
                return false;
              }
            
              // If all fields are filled, proceed with form submission
              // You can use AJAX or other methods here to submit the form data to the server
              // For this example, we will just submit the form normally
              this.submit();
            });