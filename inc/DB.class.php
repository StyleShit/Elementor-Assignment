<?php

class DB
{
    // singleton instances
    private static $instances = [];

    // database JSON file
    private const DB_DIR = __DIR__ . '/../db';
    private $dbFile;

    // current database data in RAM
    private $data = [];


    // initialize DB instance
    private function __construct( $table )
    {
        $this->dbFile = self::DB_DIR . '/' . $table . '.json';

        if( !file_exists( $this->dbFile ) )
        {
            if( !is_dir( self::DB_DIR ) )
            {
                mkdir( self::DB_DIR );
            }

            file_put_contents( $this->dbFile, '[]' );
            $this->data = [];
        }

        else
        {
            $content = file_get_contents( $this->dbFile );
            $content = trim( $content );
            $this->data = $content ? json_decode( $content ) : [];
        }
    }


    // get DB singleton instance
    public static function getInstance( $table = 'users' )
    {
        $table = strtolower( $table );

        if( !array_key_exists( $table, self::$instances ) )
        {
            self::$instances[$table] = new self( $table );
        }
     
        return self::$instances[$table];
    }


    // alias of getInstance
    public static function table( $table = 'users' )
    {
        return self::getInstance( $table );
    }


    // write data from RAM to the database file
    public function save()
    {
        try
        {
            $json = json_encode( $this->data );
            file_put_contents( $this->dbFile, $json );
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
        $results = array_filter( $this->data, function( $object ) use ( $key, $value )
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

        return array_values( $results );
    }


    // find objects in database with multiple where conditions
    public function whereAnd( $conditions )
    {
        $results = array_filter( $this->data, function( $object ) use ( $conditions )
        {

            $flg = true;

            foreach( $conditions as $key => $value )
            {
                // callback condition
                if( is_callable( $value ) )
                {
                    $flg = $flg && $value( $object->$key );
                }

                // regular condition
                else
                {
                    $flg = $flg && property_exists( $object, $key ) && $object->{ $key } == $value;
                }
            }

            return $flg;

        });

        return array_values( $results );
    }



    /**
     * Utils
     */

    // find object by id
    private function findObject( $id )
    {
        foreach( $this->data as $i => $object )
        {
            if( $object->id == $id )
            {
                return [ $i, $object ];
            }
        }

        return [ null, null ];
    }
    
}