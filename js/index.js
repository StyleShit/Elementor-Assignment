const onlineUsers   = _( '.online-users tbody' );
const updatedAt     = _( '.last-updated span' );

window.addEventListener( 'load', ( e ) => {


    // fetch online users every 3 seconds
    fetchOnlineUsers();

    setInterval( () => {

        fetchOnlineUsers();

    }, 3000 );

});


// fetch online users into the table
const fetchOnlineUsers = () => {

    _apiGetOnlineUsers()
        .then( res => {

            const users = res.data;
            let output = '';

            // build the table structure
            users.forEach( ( user ) => {

                const loggedAt = new Date( user.loggedAt * 1000 ).toLocaleString();

                const row = `
                    <tr>
                        <td>${ user.email }</td>
                        <td>${ loggedAt  }</td>
                        <td>${ user.ip }</td>
                    </tr>
                `;

                output += row;

            });


            // set the data into the table
            onlineUsers.innerHTML = output;
            updatedAt.innerText = new Date().toLocaleTimeString();

        });

};