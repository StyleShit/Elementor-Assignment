// form inputs
const email     = _( '#email' );
const password  = _( '#password' );
const loginForm = _( '.login-form' );
const loader    = _( '.loader' );


// handle form submit using AJAX
loginForm.addEventListener( 'submit', ( e ) => {

    e.preventDefault();

    const data = {

        email: email.value.trim(),
        password: password.value.trim()

    }

    loader.classList.add( 'shown' );

    _apiLoginUser( data )

        .then( res => {

            if( res.error )
            {
                loader.classList.remove( 'shown' );
                toast( res.error, 'error' );
                return;
            }
            
            window.location = './';

        });

});