const onlineUsers       = _( '.online-users tbody' );
const updatedAt         = _( '.last-updated span' );
const updateLoader      = _( '.update-loader' );
const userName          = _( '.user-name' );
const logoutButton      = _( '.logout-button' );
const modal             = _( '.modal' );
const closeModalButton  = _( '.close-modal-button' );
const modalTitle        = _( '.modal-title h2' );
const modalContent      = _( '.modal-content p' );
const overlay           = _( '.overlay' );
const dashboardLoader   = _( '.dashboard-loader' );


/**
 * Event listeners
 */
window.addEventListener( 'load', async ( e ) => {

    dashboardLoader.classList.add( 'shown' );

    const currentUser = _apiGetCurrentUser();

    // add welcome message
    userName.innerText = currentUser.userName.escape();

    // set user as online
    await _apiGoOnline();

    // fetch online users every 3 seconds
    fetchOnlineUsers();

    dashboardLoader.classList.remove( 'shown' );

    setInterval( () => { fetchOnlineUsers(); }, 3000 );

});


window.addEventListener( 'beforeunload', async ( e ) => {

    // set user as offline
    await _apiGoOffline();

});


window.addEventListener( 'visibilitychange', async ( e ) => {

    // set user as offline
    if( document.visibilityState === 'hidden' )
    {
        await _apiGoOffline();
    }

    // set user as online
    else if( document.visibilityState === 'visible' )
    {
        await _apiGoOnline();
    }

});


// logout user
logoutButton.addEventListener( 'click', ( e ) => {

    e.preventDefault();

    dashboardLoader.classList.add( 'shown' );
    
    _apiLogoutUser().then( () => { window.location = './'; });

});


// close modal
overlay.addEventListener( 'click', () => { hideModal(); });
closeModalButton.addEventListener( 'click', () => { hideModal(); });


/**
 * Functions
 */

// fetch online users into the table
const fetchOnlineUsers = () => {

    updateLoader.classList.add( 'shown' );

    const currentUser = _apiGetCurrentUser();

    _apiGetOnlineUsers().then(( res ) => {

        const users = res.data;
        let output = '';

        // build the table structure
        users.forEach(( user ) => {

            const loggedAt = new Date( user.loggedAt * 1000 ).toLocaleString();
            const userName = currentUser.id === user.id ? '<strong>You</strong>' : user.userName.escape();

            const row = `
                <tr data-user-id="${ user.id }">
                    <td>${ userName }</td>
                    <td>${ loggedAt  }</td>
                    <td>${ user.ip.escape() }</td>
                </tr>
            `;

            output += row;

        });


        // set the data into the table
        onlineUsers.innerHTML = output;
        updatedAt.innerText = new Date().toLocaleTimeString();

        bindUserClick();

        // use timeout to prevent instant loader removal
        setTimeout( () => { updateLoader.classList.remove( 'shown' ); }, 500 );

    });

};


// add click event to users rows
const bindUserClick = () => {

    const rows   = __( '.online-users tbody tr' );

    rows.forEach(( row ) => {

        row.addEventListener( 'click', () => {

            const userId = row.dataset.userId;
            showUserModal( userId );

        });

    });

}


// show user data in a modal by id
const showUserModal = ( id ) => {

    dashboardLoader.classList.add( 'shown' );
    
    _apiGetUserData({ id }).then(( res ) => {

        dashboardLoader.classList.remove( 'shown' );

        if( !res.error )
        {
            const userData = res.data[0];
            const registrationDate = new Date( userData.createdAt * 1000 ).toLocaleDateString();
            
            modalTitle.innerText = `${ userData.userName } (${ userData.email })`;
            modalContent.innerHTML = `
            <strong>User-Agent: </strong>${ userData.userAgent.escape() }<br />
            <strong>Resgistration Date: </strong>${ registrationDate }<br />
            <strong>Logins Count: </strong>${ userData.loginsCount }<br />
            `;

            showModal();
        }

    });

}


const showModal = () => {

    modal.classList.add( 'shown' );
    overlay.classList.add( 'shown' );

}

const hideModal = () => {

    modal.classList.remove( 'shown' );
    overlay.classList.remove( 'shown' );
    
}