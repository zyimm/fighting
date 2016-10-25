<?php
/**
 * 通用的树型类，可以生成任何树型结构
 * ZYIMM
 * @version 1.0
 * 下午1:58:26
 * 版权所有@copyright 2016 www.php400.cn
 */
namespace Lib;

class Tree
{

    private $categorys = array();

    private $parentArray = array();

    public function __construct($arr, $key_name = array())
    {
         if(empty($key_name)){
             $key_name=array(
                 'id','pid','name'
             );
         }
        foreach ($arr as $v) {
            $this->setOption($v[$key_name[0]], $v[$key_name[1]], $v[$key_name[2]]);
        }
    }

    public function setOption($id, $pid, $val)
    {
        $this->categorys[$id] = $val;
        $this->parentArray[$id] = $pid;
    }

    public function getAllChilds($id = 0)
    {
        $childArray = array();
        $childs = $this->getChilds($id);
        // 递归实现遍历所有栏目id
        foreach ($childs as $child) {
            $childArray[] = $child;
            $childArray = array_merge($childArray, $this->getAllChilds($child));
        }
        return $childArray;
    }
    // 获取对应id下的子栏目
    private function getChilds($id)
    {
        //
        $childs = array();
        foreach ($this->parentArray as $child => $parent) {
            if ($parent == $id)
                $childs[] = $child;
        }
        return $childs;
    }

    public function getValue($id)
    {
        return $this->categorys[$id];
    }
    // 递归实现遍历栏目层次
    private function getLever($id)
    {
        $parents = array();
        if (array_key_exists($this->parentArray[$id], $this->parentArray)) {
            $parents[] = $this->parentArray[$id];
            $parents = array_merge($parents, $this->getLever($this->parentArray[$id]));
        }
        return $parents;
    }

    public function setMark($id)
    {
        $num = count($this->getLever($id));
        $mark = str_repeat('&nbsp;&nbsp;', $num);
        return $mark . '|--';
    }

    public function getTrees()
    {
        $row = array();
        foreach ($this->getAllChilds() as $id) {
            $row[$id] = $this->setMark($id) . $this->getValue($id);
        }
        return $row;
    }
}