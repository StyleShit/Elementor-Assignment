/*
    Simple toasts manager
*/

let _toastsContainer;

window.addEventListener( 'load', () => {

    _toastsContainer = document.createElement( 'div' );
    _toastsContainer.className = 'toasts-container';

    document.body.appendChild( _toastsContainer );

});


// create new toast
const toast = ( message, type = 'info', timeout = 3000 ) => {

    let newToast = document.createElement( 'div' );
        newToast.className = `toast toast-${ type }`;
        newToast.innerText = message;

    _toastsContainer.appendChild( newToast );

    setTimeout( () => {
        
        newToast.classList.add( 'fading-out' );
        setTimeout( () => { _toastsContainer.removeChild( newToast ); }, 400 );

    }, timeout );

}