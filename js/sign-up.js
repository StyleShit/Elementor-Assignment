// form inputs
const email             = _( '#email' );
const userName          = _( '#user-name' );
const password          = _( '#password' );
const passwordConfirm   = _( '#password-confirm' );
const submitButton      = _( 'input[type=submit]' );


// handle form submit using AJAX
submitButton.addEventListener( 'click', ( e ) => {

    e.preventDefault();

    const data = {

        email: email.value.trim(),
        userName: userName.value.trim(),
        password: password.value.trim(),
        passwordConfirm: passwordConfirm.value.trim()

    }

    _apiRegisterUser( data )

        .then( res => {

            if( res.error )
            {
                alert( res.error );
                return;
            }
            
            window.location = './';

        });

});