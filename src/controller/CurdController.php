<?php

namespace Gorden\Curd\Controller;

use support\Request;
use support\Response;
use Gorden\Curd\Common\Util;
use support\exception\BusinessException;

class CurdController extends BaseController
{
    /**
     * 查询
     * @param Request $request
     * @return Response
     * @throws BusinessException
     */
    public function select(Request $request): Response
    {
        try {
            [$where, $format, $limit, $field, $order] = $this->selectInput($request);
            $query = $this->specifyConditions($where, $field, $order);
            return $this->doSelect($query, $format, $limit);
        } catch (\Exception $e) {
            return Util::jsonFail(config('app.debug', false) ? $e->getMessage() : "查询异常~");
        }
    }

    /**
     * 添加
     * @param Request $request
     * @return Response
     * @throws BusinessException
     */
    public function insert(Request $request): Response
    {
        try {
            $scene = $this->scene ?? 'insert';
            if ($this->validateSwitch() && $this->validate()->scene($scene)->check($request->post())) {
                throw new BusinessException($this->validate->getError());
            }
            $data = $this->inputFilter($request->post());

            return $this->doInsert($data);
        } catch (\PDOException $PDOException) {
            return Util::jsonFail(config('app.debug', false) ? $PDOException->getMessage() : '数据写入失败~');
        } catch (BusinessException $e) {
            return Util::jsonFail($e->getMessage());
        } catch (\Exception $e) {
            return Util::jsonFail(config('app.debug', false) ? $e->getMessage() : '数据写入失败~');
        }
    }

    /**
     * 更新
     * @param Request $request
     * @return Response
     * @throws BusinessException
     */
    public function update(Request $request): Response
    {
        try {
            $scene = $this->scene ?? 'update';
            if ($this->validateSeitch && !$this->validate()->scene($scene)->check($request->post())) {
                return Util::jsonFail($this->validateClass->getError());
            }
            [$id, $data] = $this->updateInput($request);
            $this->doUpdate($id, $data);
            return json_success('success');
        } catch (\PDOException $PDOException) {
            return Util::jsonFail(config('app.debug', false) ? $PDOException->getMessage() : '数据写入失败~');
        } catch (BusinessException $e) {
            return Util::jsonFail($e->getMessage());
        } catch (\Exception $e) {
            return Util::jsonFail(config('app.debug', false) ? $e->getMessage() : '数据写入失败~');
        }
    }

    /**
     * 删除
     * @param Request $request
     * @return Response
     * @throws BusinessException
     */
    public function delete(Request $request): Response
    {
        try {
            $ids = $this->deleteInput($request);
            $this->doDelete($ids);
            return json_success('success');
        } catch (\PDOException $PDOException) {
            return Util::jsonFail(config('app.debug', false) ? $PDOException->getMessage() : '数据写入失败~');
        } catch (BusinessException $e) {
            return Util::jsonFail($e->getMessage());
        } catch (\Exception $e) {
            return Util::jsonFail(config('app.debug', false) ? $e->getMessage() : '数据写入失败~');
        }
    }
}