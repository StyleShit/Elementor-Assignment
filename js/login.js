// form inputs
const email     = _( '#email' );
const password  = _( '#password' );
const loginForm = _( '.login-form' );


// handle form submit using AJAX
loginForm.addEventListener( 'submit', ( e ) => {

    e.preventDefault();

    const data = {

        email: email.value.trim(),
        password: password.value.trim()

    }

    _apiLoginUser( data )

        .then( res => {

            if( res.error )
            {
                alert( res.error );
                return;
            }
            
            window.location = './';

        });

});