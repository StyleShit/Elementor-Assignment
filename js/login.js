// form inputs
const email             = _( '#email' );
const password          = _( '#password' );
const submitButton      = _( 'input[type=submit]' );


// handle form submit using AJAX
submitButton.addEventListener( 'click', ( e ) => {

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