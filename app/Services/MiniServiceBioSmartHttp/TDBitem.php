<?php

namespace App\Services\MiniServiceBioSmartHttp;

trait TDBitem
{
    static function getColumns($full=false)
    {
        global $mysqli_self_db;

        $query = 'SHOW COLUMNS FROM '. self::table;
        $sqlResult = $mysqli_self_db->query($query);

        if ($sqlResult)
        {
            if ( $full=='FULL' )
            {
                while ( $row = $sqlResult->fetch_object() )
                    $arResult[$row->Field] = $row;
            }
            else
            {
                while ( $row = $sqlResult->fetch_object() )
                    $arResult[] = $row->Field;
            }
        }

        return $arResult;
    }

    protected static function explodeFieldType ($varFieldType)
    {
        $varFieldType = str_replace(')','',$varFieldType);
        $arFieldType = explode('(',$varFieldType);
        $arFieldTypeFinal['type'] = $arFieldType[0];
        $arFieldTypeFinal['length'] = isset($arFieldType[1]) ? $arFieldType[1] : null;
        return $arFieldTypeFinal;
    }

    protected static function checkFieldValue ($varValue,$varFieldType)
    {
        global $mysqli_self_db;


        $arFieldType = self::explodeFieldType($varFieldType);
        if($arFieldType['type']=='int' || $arFieldType['type']=='tinyint' )
        {
            if (!is_int($varValue) && !ctype_digit($varValue))
                $result = false;
            else
                $result = intval($varValue);

        }
        elseif ( $arFieldType['type']=='varchar' || $arFieldType['type']=='text'  )
            $result = $mysqli_self_db->real_escape_string($varValue);
        else
            $result = $varValue;

        return $result;
    }

    static function add($varAddFields=false)
    {
        global $mysqli_self_db;

        $avColumns = self::getColumns();
        $avColumnsFull = self::getColumns('FULL');

        if ( is_array($varAddFields) && count($varAddFields)>0 )
        {
            foreach( $varAddFields as $key => $varAddFieldValue )
            {
                if ( in_array($key,$avColumns) )
                {
                    $varAddFieldValue = self::checkFieldValue($varAddFieldValue,$avColumnsFull[$key]->Type);
                    if($varAddFieldValue)
                    {
                        $arSqlFields[] = "`$key`";
                        $arSqlValues[] = "'$varAddFieldValue'";
                    }
                }
                else
                    unset($varAddFields[$key]);
            }
        }
        $strSqlFields = ($arSqlFields ? implode(', ',$arSqlFields) : '');
        $strSqlValues = ($arSqlValues ? implode(', ',$arSqlValues) : '');
        $query = "INSERT INTO ".self::table." ({$strSqlFields}) VALUES ({$strSqlValues});";

        $sqlResult = $mysqli_self_db->query($query);

        if ($sqlResult)
            return $mysqli_self_db->insert_id;
        else
            return false;
    }

    static function get ($arSelectedFields=false, $arFilter=false, $order=false, $arLimit=false)
    {
        global $mysqli_self_db;

        $avColumns = self::getColumns();
        $avColumns[] = '*';
        $avColumnsFull = self::getColumns('FULL');

        if ( is_array($arSelectedFields) && count($arSelectedFields)>0 )
        {
            foreach( $arSelectedFields as $key=>$arSelectedField )
            {
                if ( !in_array($arSelectedField,$avColumns) )
                    unset($arSelectedFields[$key]);
            }

            $strSqlFields = ($arSelectedFields ? implode(', ',$arSelectedFields) : '');
        }

        if ( is_array($arFilter) && count($arFilter)>0 )
        {
            foreach( $arFilter as $key => $varFilterValue )
            {
                if ( in_array($key,$avColumns) )
                {
                    $varFilterValue = self::checkFieldValue($varFilterValue,$avColumnsFull[$key]->Type);
                    if($varFilterValue)
                        $arSqlFilters[] = $key."='$varFilterValue'";
                }
                else
                    unset($arFilter[$key]);
            }
            $strSqlFilters = ($arSqlFilters ? implode('AND ',$arSqlFilters) : '');
        }
        elseif ( is_string($arFilter) )
            $strSqlFilters = $arFilter;

        if ( !empty($strSqlFields) )
        {
            $query = "select {$strSqlFields} from ".self::table;
            if (!empty($strSqlFilters))
                $query .= " where $strSqlFilters";
            if ( is_array($order) )
                $query .= " order by {$order[0]} {$order[1]}";
            if ( is_array($arLimit) && sizeof($arLimit))
                $query .= " limit {$arLimit[0]},{$arLimit[1]}";
            elseif ( is_string($arLimit) && ctype_digit($arLimit) || is_int($arLimit) )
                $query .= " limit $arLimit";
            $query .= ";";

            $sqlResult = $mysqli_self_db->query($query);
            if ( $sqlResult->num_rows > 0 )
            {
                while ( $row = $sqlResult->fetch_object())
                {
                    $object = new self();
                    foreach ($row as $key => $value)
                        $object->$key = $value;
                    $arResult[] = $object;
                }

                return $arResult;
            }
            else
                return [];
        }
        else
            throw new Exception('Отсутствуют корректные поля для выборки данных');
    }


    static function update($id,$varAddFields=false)
    {
        global $mysqli_self_db;

        $avColumns = self::getColumns();
        $avColumnsFull = self::getColumns('FULL');

        if ( is_array($varAddFields) && count($varAddFields)>0 )
        {
            foreach( $varAddFields as $key => $varAddFieldValue )
            {
                if ( in_array($key,$avColumns) )
                {
                    $varAddFieldValue = self::checkFieldValue($varAddFieldValue,$avColumnsFull[$key]->Type);
                    if($varAddFieldValue)
                        $arSqlFields[] = $key."='$varAddFieldValue'";
                }
                else
                    unset($varAddFields[$key]);
            }
        }
        $strSqlFields = ($arSqlFields ? implode(', ',$arSqlFields) : '');
        $query = "update ".self::table." set {$strSqlFields} where id={$id};";

        $sqlResult = $mysqli_self_db->query($query);

        return $sqlResult;
    }

    static function delete($id)
    {
        global $mysqli_self_db;

        return $mysqli_self_db->query("delete from zadakur where id={$id}");
    }
}
