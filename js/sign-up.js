// form inputs
const email             = _( '#email' );
const userName          = _( '#user-name' );
const password          = _( '#password' );
const passwordConfirm   = _( '#password-confirm' );
const signUpForm        = _( '.sign-up-form' );
const loader            = _( '.loader' );


// handle form submit using AJAX
signUpForm.addEventListener( 'submit', ( e ) => {

    e.preventDefault();

    const data = {

        email: email.value.trim(),
        userName: userName.value.trim(),
        password: password.value.trim(),
        passwordConfirm: passwordConfirm.value.trim()

    }

    
    if( !isValidEmail( data.email ) )
    {
        alert( 'Invalid email address' );
        return;
    }

    if( data.password !== data.passwordConfirm )
    {
        alert( 'Passwords do not match' );
        return;
    }

    loader.classList.add( 'shown' );

    _apiRegisterUser( data )

        .then( res => {

            if( res.error )
            {
                loader.classList.remove( 'shown' );
                alert( res.error );
                return;
            }
            
            window.location = './';

        });

});