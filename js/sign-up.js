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
        toast( 'Invalid email address', 'error' );
        return;
    }

    if( data.password !== data.passwordConfirm )
    {
        toast( 'Passwords do not match', 'error' );
        return;
    }

    loader.classList.add( 'shown' );

    _apiRegisterUser( data )

        .then( res => {

            if( res.error )
            {
                loader.classList.remove( 'shown' );
                toast( res.error, 'error' );
                return;
            }
            
            toast( 'Created successfully! You are being redirected', 'success' );

            setTimeout( () => { window.location = './'; }, 3000 );

        });

});