<?php

class DB
{
    // singleton instance
    private static $instance = null;

    // database JSON file name
    private const DB_FILE = __DIR__ . '/users.json';

    // current database data in RAM
    private $data = [];


    // initialize DB instance
    private function __construct()
    {
        if( !file_exists( self::DB_FILE ) )
        {
            file_put_contents( self::DB_FILE, '[]' );
            $this->data = [];
        }

        else
        {
            $content = file_get_contents( self::DB_FILE );
            $content = trim( $content );
            $this->data = $content ? json_decode( $content ) : [];
        }
    }


    // get DB singleton instance
    public static function getInstance()
    {
        if( self::$instance == null )
        {
            self::$instance = new self();
        }
     
        return self::$instance;
    }


    // write data from RAM to the database file
    public function save()
    {
        try
        {
            $json = json_encode( $this->data );
            file_put_contents( self::DB_FILE, $json );
        }

        catch( Exception $e )
        {
            die( $e->getMessage() );
        }

        return $this;
    }


    // insert object to database
    public function insert( $object )
    {
        $object['id'] = uniqid( '_' );
        $object['createdAt'] = time();
        $object['updatedAt'] = time();
        $object = ( object ) $object;

        $this->data[] = $object;
        $this->save();

        return $object;
    }


    // update object by id
    public function update( $id, $updatedObject )
    {
        $updatedObject = ( object ) $updatedObject;
        $updatedObject->updatedAt = time();

        [ $i, $current ] = $this->findObject( $id );

        if( !$current )
        {
            return false;
        }


        $this->data[$i] = ( object ) array_merge(
            ( array ) $current,
            ( array ) $updatedObject
        );


        $this->save();

        return $this;
    }


    // delete object by id
    public function delete( $id )
    {
        [ $i, $object ] = $this->findObject( $id );

        if( !$object )
        {
            return $this;
        }

        unset( $this->data[$i] );

        $this->save();

        return $this;
    }


    // find objects in database by key & value
    public function where( $key, $value )
    {
        return array_filter( $this->data, function( $object ) use ( $key, $value )
        {

            // callback condition
            if( is_callable( $value ) )
            {
                return $value( $object->$key );
            }

            // regular condition
            else
            {
                return property_exists( $object, $key ) && $object->{ $key } == $value;
            }

        });
    }



    /**
     * Utils
     */

    // find object by id
    private function findObject( $id )
    {
        foreach( $this->data as $i => $object )
        {
            if( $object->id == $id)
            {
                return [ $i, $object ];
            }
        }

        return [ null, null ];
    }
    
}