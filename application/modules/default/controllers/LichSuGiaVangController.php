<?php

class LichSuGiaVangController extends NHK_DefaultController {

    public function init() {
        //$this->require_authenticated();
        $this->setLayout('main', 'default');
        $this->view->menu = 'home';
        $this->_model = new LichSuGiaVang();
        $this->_itemPerPage = 15;
        $this->_pageRange = 4;
    }

    public function indexAction() {
        //$controller = $this->getRequest()->getControllerName();
        //$action = $this->getRequest()->getActionName();

        $this->view->headTitle("Lịch Sử Giá Vàng");
    }

    public function lichsuAction() {
        $this->noRender();
        $this->noLayout();
        if ($this->_request->isPost()) {
            $params = $this->_request->getParams();
            $data = $this->_model->getAll(array('ngay' => $params['ngay']));

            $result = '';
            //print_r($data);
            foreach ($data as $item) {
                $giavang = (array) json_decode($item->giavang, true);
                $result .= $this->drawTableGiaVang($giavang, $item->created_at);
                //$result .= $item->giavang;
                //print_r($giavang);
            }


            echo $result;
        }
    }

    public function drawTableGiaVang($data, $time) {
        $str = '';
        $format_time = date('d-m-Y H:i:s', strtotime($time));
        $str .= "<p>Cập Nhật: {$format_time}</p>";
        $str .= '<table class="table_gia" border="1"><thead>
        <tr><th>Loại</th><th>Chất Lượng ‰</th><th>Mua Vào</th>
            <th>Bán Ra</th></tr></thead><tbody>';
        $str .= "
        <tr>
            <th>Vàng 24K</th>
            <td class='loai_vang'>999</td>
            <td>{$data["v999"]["m"]}</td>
            <td>{$data["v999"]["b"]}</td>
        </tr>
        <tr>
            <th>Vàng Nữ Trang</th>
            <td class='loai_vang'>980</td>
            <td id='v980_m'>{$data['v980']['m']}</td>
            <td id='v980_b'>{$data['v980']['b']}</td>
        </tr>
        <tr>
            <th>Vàng Nữ Trang</th>
            <td class='loai_vang'>960</td>
            <td id='v960_m'>{$data['v960']['m']}</td>
            <td id='v960_b'>{$data['v960']['b']}</td>
        </tr>
        <tr class='vang18k'>
            <th>Vàng Nữ Trang</th>
            <td class='loai_vang'>750</td>
            <td id='v750_m'>{$data['v750']['m']}</td>
            <td id='v750_b'>{$data['v750']['b']}</td>
        </tr>
        <tr>
            <th>Vàng Nữ Trang</th>
            <td class='loai_vang'>680</td>
            <td id='v680_m'>{$data['v680']['m']}</td>
            <td id='v680_b'>{$data['v680']['b']}</td>
        </tr>
        <tr>
            <th>Vàng Nữ Trang</th>
            <td class='loai_vang'>610</td>
            <td id='v610_m'>{$data['v610']['m']}</td>
            <td id='v610_b'>{$data['v610']['b']}</td>
        </tr>
        <tr>
            <th>Vàng Nữ Trang</th>
            <td class='loai_vang'>585</td>
            <td id='v585_m'>{$data['v585']['m']}</td>
            <td id='v585_b'>{$data['v585']['b']}</td>
        </tr>
        <tr class='giasjc'>
                    <th >SJC</th>
                    <td class='loai_vang'>9999</td>
                    <td id='sjc_m'>{$data['sjc']['m']}</td>
                    <td id='sjc_b'>{$data['sjc']['b']}</td>
                </tr>
      
    </tbody>
</table>
            <hr/>   ";

        return $str;
    }

    public function detailAction() {
        $params = $this->_request->getParams();
        if (isset($params['id'])) {
            $this->view->data = $this->_model->get($params['id']);
            $this->view->headTitle("{$this->view->data->title} | Mai Quyên");
        } else {
            $this->redirect(NHK_URL::getLinkControllerDefault('news'));
        }
    }
    
    

}
?>