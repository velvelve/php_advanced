<?php
function foo()
{
    try {
        throw new Exception("error ");
    } catch (Exception $exception) {
        echo "error 1 \n";
        echo $exception->getMessage();
        return false;
    }
    return true;
}


try {
    echo "start\n";
    var_dump(foo());
    echo "end\n";
} catch (Exception $exception) {
    echo "error 2 \n";
    echo $exception->getMessage();
}
