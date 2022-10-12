<?php

namespace App\Http\Controllers;

use App\Bancos_Layout_Model;
use App\Bancos_Pruebas_Model;
use App\Credito_Layout_Model;
use App\Credito_Pruebas_Model;
use App\SAP_Layout_Model;
use App\SAP_Pruebas_Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;

class LayoutsController extends Controller
{
    public function guardarTesoreria(Request $request){
        DB::table("temporal_tesoreria")
        ->where("usuario", "=", Session::get('user'))
        ->delete();

    	Session::forget("LayoutTeso");

        $prueba = DB::table("bancos_l_tesoreria")
        ->get();

        return response()->json($prueba);
    }

    public function guardarCredito(Request $request){
    	DB::table("temporal_credito")
        ->where("usuario", "=", Session::get('user'))
        ->delete();

        Session::forget("LayoutCred");

        $prueba = DB::table("bancos_l_credito")
        ->get();

        return response()->json($prueba);
    }

    public function guardarSAP(Request $request){
        DB::table("temporal_SAP")
        ->where("usuario", "=", Session::get('user'))
        ->delete();

        Session::forget("LayoutSAP");

        $prueba = DB::table("bancos_l_SAP")
        ->get();

        return response()->json($prueba);
    }

    public function obtenerSAP(Request $request)
    {
        DB::table("bancos_p_SAP")->insert([
            "id_ls" => $request->get("op"),
            "nombre" => $request->get("nombre"),
            "hoja_sap" => $request->get("hojaSap"),
            "hoja_bancos" => $request->get("hojaBancos"),
            "ID" => $request->get("id_cliente"),
            "FOLIO" => $request->get("clearing"),
            "MONEDAPAGO" => $request->get("moneda_pago"),
            "MONTOPAGO" => $request->get("monto_pago"),
            "TIPOCAMBIOP" => $request->get("tipocambio_pago"),
            "FOLIOS" => $request->get("folios"),
            "PARCIAL" => $request->get("parcialidad"),
            "TIPODOC" => $request->get("dtipo"),
            "FECHAPAGO" => $request->get("fecha_pago"),
            "NUMREGIDTRIB" => $request->get("numregidtrib"),
            "REFERENCE" => $request->get("reference"),
            "ASSIGNMENT" => $request->get("assignment"),
            "MONTOPAGOMXN" => $request->get("montomxn_pago"),
            "IMPUESTO" => $request->get("impuesto")
        ]);

        Session::put("LayoutSAP", $request->get("op"));
        Session::put("actualiza", 1);

        return response()->json(
            ["layout" => $request->get("op")]
        );
    }

    public function obtenerCredito(Request $request)
    {
        DB::table("bancos_p_credito")->insert([
            "id_lc" => $request->get("op"),
            "nombre" => $request->get("nombre"),
            "folio" => $request->get("folio"),
            "clearing" => $request->get("clearing"),
            "parcialidad" => $request->get("parcialidad"),
            "moneda" => $request->get("moneda"),
            "tipo_cambio" => $request->get("tipo_cambio"),
            "impsaldoant" => $request->get("impsaldoant"),
            "imppagado" => $request->get("imppagado")
        ]);

        Session::put("LayoutCred", $request->get("op"));
        Session::put("actualiza", 1);

        return response()->json(
            ["layout" => $request->get("op")]
        );
    }

    public function editoSAP(Request $request)
    {
        $sap = DB::table("bancos_l_SAP")
        ->where("id_ls", "=", $request->get("id"))
        ->first();

        return response()->json($sap);
    }

    public function editoCredito(Request $request)
    {
        $sap = DB::table("bancos_l_credito")
        ->where("id_lc", "=", $request->get("id"))
        ->first();

        return response()->json($sap);
    }

    public function editoTesoreria(Request $request)
    {
        $sap = DB::table("bancos_l_tesoreria")
        ->where("id_lt", "=", $request->get("id"))
        ->first();

        return response()->json($sap);
    }

    public function eliminoSAP(Request $request)
    {
        $sap = DB::table("bancos_l_SAP")
        ->where("id_ls", "=", $request->get("id"))
        ->delete();

        $sap = DB::table("bancos_l_SAP")
        ->get();

        return response()->json($sap);
    }

    public function eliminoCredito(Request $request)
    {
        $sap = DB::table("bancos_l_credito")
        ->where("id_lc", "=", $request->get("id"))
        ->delete();

        $sap = DB::table("bancos_l_credito")
        ->get();

        return response()->json($sap);
    }

    public function eliminoTesoreria(Request $request)
    {
        $sap = DB::table("bancos_l_tesoreria")
        ->where("id_lt", "=", $request->get("id"))
        ->delete();

        $sap = DB::table("bancos_l_tesoreria")
        ->get();

        return response()->json($sap);
    }

    public function obtenerTesoreria(Request $request)
    {
        DB::table("bancos_p_tesoreria")->insert([
            "id_lt" => $request->get("op"),
            "nombre" => $request->get("nombre"),
            "RFC_R" => $request->get("rfc_cliente"),
            "MONEDAP" => $request->get("moneda_pago"),
            "MONTOP" => $request->get("total_pago"),
            "FORMAP" => $request->get("forma_pago"),
            "FECHAPAG" => $request->get("fecha_pago"),
            "NUMEROPERP" => $request->get("operacion_pago"),
            "CTAORD" => $request->get("cuenta_cliente"),
            "BANCOORDEXT" => $request->get("banco_cliente"),
            "RFCCTABEN" => $request->get("rfc_banco_ben"),
            "RFCCTAORD" => $request->get("rfc_banco_cliente"),
            "CATABEN" => $request->get("cuenta_ben")
        ]);

        Session::put("LayoutTeso", $request->get("op"));
        Session::put("actualiza", 1);

        return response()->json(
            ["layout" => $request->get("op")]
        );
    }

    public function mostrarLayoutsTesoreria(Request $request){
    	
    }

    public function mostrarLayoutsCredito(Request $Request){
    	
    }

    public function mostrarLayoutsSAP(Request $request){

    }

    public function probarLayoutTesoreria(Request $request)
    {
        try {
            Excel::load($request->archivo, function($reader){
                $vieneCliente = false;
                $primero = 0;
                $ultimo = 0;

                $prueba =  DB::table("temporal_tesoreria")
                ->where("usuario", "=", Session::get('user'))
                ->delete();

                if(Session::has('actualiza')){
                    $lay = DB::table('bancos_p_tesoreria')
                    ->where('id_lt', '=', Session::get('LayoutTeso'))
                    ->first();
                }
                else{
                    $lay = DB::table('bancos_l_tesoreria')
                    ->where('id_lt', '=', Session::get('LayoutTeso'))
                    ->first();
                }

                $RFC_C = $lay->RFC_R;
                $MONTOP = $lay->MONTOP;
                $MONEDAP = $lay->MONEDAP;
                $NUMEROPERP = $lay->NUMEROPERP;
                $RFCCTABEN = $lay->RFCCTABEN;
                $CATABEN = $lay->CATABEN;
                $FORMAP = $lay->FORMAP;
                $RFCCTAORD = $lay->RFCCTAORD;
                $BANCOORDEXT = $lay->BANCOORDEXT;
                $CTAORD = $lay->CTAORD;
                $FECHAPAG = $lay->FECHAPAG;

                foreach ($reader->get() as $key => $row){
                    $datos = new Bancos_Pruebas_Model;
                    $datos->RFC_R = $row[$RFC_C];
                    $datos->MONTOP = $row[$MONTOP];
                    $datos->MONEDAP = $row[$MONEDAP];
                    $datos->NUMEROPERP = $row[$NUMEROPERP];
                    $datos->RFCCTABEN = $row[$RFCCTABEN];
                    $datos->CATABEN = $row[$CATABEN];
                    $datos->FORMAP = $row[$FORMAP];
                    $datos->RFCCTAORD = $row[$RFCCTAORD];
                    $datos->BANCOORDEXT = $row[$BANCOORDEXT];
                    $datos->CTAORD = $row[$CTAORD];
                    $datos->FECHAPAG = $row[$FECHAPAG];
                    $datos->usuario = Session::get('user');
                    $datos->save();
                }

            });

            $prueba =  DB::table("temporal_tesoreria")
            ->where("usuario", "=", Session::get('user'))
            ->get();

            return response()->json($prueba);
        } catch (\Exception $e) {
            return response()->json([
                "respuesta" => 2,
                "mensaje" => $e->getMessage()
            ]);
        }
    }

    public function probarLayoutSAP(Request $request)
    {
        try {
            if(Session::has('actualiza')){
                $lay = DB::table('bancos_p_SAP')
                ->where('id_ls', '=', Session::get('LayoutSAP'))
                ->first();
            }
            else{
                $lay = DB::table('bancos_l_SAP')
                ->where('id_ls', '=', Session::get('LayoutSAP'))
                ->first();
            }

            $hojaSap = $lay->hoja_sap;
            $hojaBancos = $lay->hoja_bancos;

            Excel::selectSheets($hojaSap)->load($request->archivo, function($reader){
                $vieneCliente = false;
                $primero = 0;
                $ultimo = 0;

                $prueba =  DB::table("temporal_SAP")
                ->where("usuario", "=", Session::get('user'))
                ->delete();

                if(Session::has('actualiza')){
                    $lay = DB::table('bancos_p_SAP')
                    ->where('id_ls', '=', Session::get('LayoutSAP'))
                    ->first();
                }
                else{
                    $lay = DB::table('bancos_l_SAP')
                    ->where('id_ls', '=', Session::get('LayoutSAP'))
                    ->first();
                }

                $hojaSap = $lay->hoja_sap;
                $hojaBancos = $lay->hoja_bancos;
                $ID = $lay->ID;
                $FOLIO = $lay->FOLIO;
                $MONEDAPAGO = $lay->MONEDAPAGO;
                $MONTOPAGO = $lay->MONTOPAGO;
                $TIPOCAMBIOP = $lay->TIPOCAMBIOP;
                $TIPODOC = $lay->TIPODOC;
                $FECHADOC = $lay->FECHAPAGO;
                $FOLIOS = $lay->FOLIOS;
                $PARCIAL = $lay->PARCIAL;
                $ASSIGNMENT = $lay->ASSIGNMENT;
                $REFERENCE = $lay->REFERENCE;
                $NUMREGIDTRIB = $lay->NUMREGIDTRIB;
                $MONTOPAGOMXN = $lay->MONTOPAGOMXN;
                $IMPUESTO = $lay->IMPUESTO;

                foreach ($reader->get() as $key => $row){
                    $sap = new SAP_Pruebas_Model;
                    $sap->id_cliente = $row[$ID];
                    $sap->FOLIO = $row[$FOLIO];
                    $sap->MONEDAPAGO = $row[$MONEDAPAGO];
                    $sap->MONTOPAGO = str_replace("-", "", $row[$MONTOPAGO]);
                    $sap->MONTOPAGOMXN = str_replace("-", "", $row[$MONTOPAGOMXN]);
                    $sap->TIPOCAMBIOP = str_replace("-", "", $row[$TIPOCAMBIOP]);
                    $sap->TIPODOC = $row[$TIPODOC];
                    $sap->FECHADOC = $row[$FECHADOC];
                    $sap->ASSIGNMENT = $row[$ASSIGNMENT];
                    $sap->REFERENCE = $row[$REFERENCE];
                    $sap->FOLIOS = substr($row[$FOLIOS], -7);
                    $sap->PARCIAL = $row[$PARCIAL];
                    $sap->NUMREGIDTRIB = $row[$NUMREGIDTRIB];
                    $sap->TAX = $row[$IMPUESTO];
                    $sap->usuario = Session::get('user');
                    $sap->save();
                }

            });

            $prueba =  DB::table("temporal_SAP")
            ->where("usuario", "=", Session::get('user'))
            ->get();

            return response()->json($prueba);
        } catch (\Exception $e) {
            return response()->json([
                "respuesta" => 2,
                "mensaje" => $e->getMessage()
            ]);
        }
    }

    public function probarLayoutCredito(Request $request)
    {
        try {
            Excel::load($request->archivo, function($reader){
                $vieneCliente = false;
                $primero = 0;
                $ultimo = 0;

                $prueba =  DB::table("temporal_credito")
                ->where("usuario", "=", Session::get('user'))
                ->delete();

                //Session::forget('actualiza');

                if(Session::has('actualiza')){
                    $lay = DB::table('bancos_p_credito')
                    ->where('id_lc', '=', Session::get('LayoutCred'))
                    ->first();
                }
                else{
                    $lay = DB::table('bancos_l_credito')
                    ->where('id_lc', '=', Session::get('LayoutCred'))
                    ->first();
                }

                $FOLIO = $lay->folio;
                $CLEARING = $lay->clearing;
                $PARCIALIDAD = $lay->parcialidad;
                $MONEDA = $lay->moneda;
                $CAMBIO = $lay->tipo_cambio;
                $IMPSALDOANT = $lay->impsaldoant;
                $IMPPAGADO = $lay->imppagado;

                foreach ($reader->get() as $key => $row){
                    $sap = new Credito_Pruebas_Model;
                    $sap->folio = $row[$FOLIO];
                    $sap->clearing = $row[$CLEARING];
                    $sap->parcialidad = $row[$PARCIALIDAD];
                    $sap->moneda = $row[$MONEDA];
                    $sap->tipo_cambio = $row[$CAMBIO];
                    $sap->impsaldoant = $row[$IMPSALDOANT];
                    $sap->imppagado = $row[$IMPPAGADO];
                    $sap->usuario = Session::get('user');
                    $sap->save();
                }

            });

            $prueba =  DB::table("temporal_credito")
            ->where("usuario", "=", Session::get('user'))
            ->get();

            return response()->json($prueba);
        } catch (\Exception $e) {
            return response()->json([
                "respuesta" => 2,
                "mensaje" => $e->getMessage()
            ]);
        }
    }

    public function cancelarLayoutSAP(Request $request)
    {
        DB::table("bancos_l_SAP")
        ->where("id_ls", "=", Session::get("LayoutSAP"))
        ->delete();
        
        Session::forget("LayoutSAP");

        return response()->json([
            "respuesta" => 0
        ]);
    }

    public function cancelarLayoutCredito(Request $request)
    {
        DB::table("bancos_l_credito")
        ->where("id_lc", "=", Session::get("LayoutCred"))
        ->delete();
        
        Session::forget("LayoutCred");

        return response()->json([
            "respuesta" => 0
        ]);
    }

    public function cancelarLayoutTesoreria(Request $request)
    {
        DB::table("bancos_l_tesoreria")
        ->where("id_lt", "=", Session::get("LayoutTeso"))
        ->delete();
        
        Session::forget("LayoutTeso");

        return response()->json([
            "respuesta" => 0
        ]);
    }

    public function pruebasTesoreria(Request $request){
        $id = 0;

        $id = DB::table("bancos_l_tesoreria")->insertGetId([
            "nombre" => $request->get("nombre"),
            "RFC_R" => $request->get("rfc_cliente"),
            "MONEDAP" => $request->get("moneda_pago"),
            "MONTOP" => $request->get("total_pago"),
            "FORMAP" => $request->get("forma_pago"),
            "FECHAPAG" => $request->get("fecha_pago"),
            "NUMEROPERP" => $request->get("operacion_pago"),
            "CTAORD" => $request->get("cuenta_cliente"),
            "BANCOORDEXT" => $request->get("banco_cliente"),
            "RFCCTABEN" => $request->get("rfc_banco_ben"),
            "RFCCTAORD" => $request->get("rfc_banco_cliente"),
            "CATABEN" => $request->get("cuenta_ben")
        ]);

        Session::put("LayoutTeso", $id);
        if(Session::has('actualiza')){
            Session::forget("actualiza");
        }

        return response()->json(
            ["layout" => $id]
        );
    }

    public function pruebasCredito(Request $request){
        $id = 0;

        $id = DB::table("bancos_l_credito")->insertGetId([
            "nombre" => $request->get("nombre"),
            "folio" => $request->get("folio"),
            "clearing" => $request->get("clearing"),
            "parcialidad" => $request->get("parcialidad"),
            "moneda" => $request->get("moneda"),
            "tipo_cambio" => $request->get("tipo_cambio"),
            "impsaldoant" => $request->get("impsaldoant"),
            "imppagado" => $request->get("imppagado")
        ]);

        Session::put("LayoutCred", $id);
        if(Session::has('actualiza')){
            Session::forget("actualiza");
        }

        return response()->json(
            ["layout" => $id]
        );
    }

    public function pruebasSAP(Request $request){
        $id = 0;

        $id = DB::table("bancos_l_SAP")->insertGetId([
            "nombre" => $request->get("nombre"),
            "hoja_sap" => $request->get("hojaSap"),
            "hoja_bancos" => $request->get("hojaBancos"),
            "ID" => $request->get("id_cliente"),
            "FOLIO" => $request->get("clearing"),
            "MONEDAPAGO" => $request->get("moneda_pago"),
            "MONTOPAGO" => $request->get("monto_pago"),
            "TIPOCAMBIOP" => $request->get("tipocambio_pago"),
            "FOLIOS" => $request->get("folios"),
            "PARCIAL" => $request->get("parcialidad"),
            "TIPODOC" => $request->get("dtipo"),
            "FECHAPAGO" => $request->get("fecha_pago"),
            "NUMREGIDTRIB" => $request->get("numregidtrib"),
            "REFERENCE" => $request->get("reference"),
            "ASSIGNMENT" => $request->get("assignment"),
            "MONTOPAGOMXN" => $request->get("montomxn_pago"),
            "IMPUESTO" => $request->get("impuesto"),
            "USOCFDI" => $request->get("usocfdi"),//Aqui se modifico
            "TASAIVA" => $request->get("tasaiva"),//Aqui se modifico
            "TASARETENCION" => $request->get("tasaretencion"),//Aqui se modifico
        ]);

        Session::put("LayoutSAP", $id);
        if(Session::has('actualiza')){
            Session::forget("actualiza");
        }

        return response()->json(
            ["layout" => $id]
        );
        
    }

    public function actualizarLayoutTesoreria(Request $request)
    {
        DB::table("bancos_l_tesoreria")
        ->where("id_lt", "=", $request->get("op"))
        ->update([
            "nombre" => $request->get("nombre"),
            "RFC_R" => $request->get("rfc_cliente"),
            "MONEDAP" => $request->get("moneda_pago"),
            "MONTOP" => $request->get("total_pago"),
            "FORMAP" => $request->get("forma_pago"),
            "FECHAPAG" => $request->get("fecha_pago"),
            "NUMEROPERP" => $request->get("operacion_pago"),
            "CTAORD" => $request->get("cuenta_cliente"),
            "BANCOORDEXT" => $request->get("banco_cliente"),
            "RFCCTABEN" => $request->get("rfc_banco_ben"),
            "RFCCTAORD" => $request->get("rfc_banco_cliente"),
            "CATABEN" => $request->get("cuenta_ben")
        ]);

        DB::table("bancos_p_tesoreria")
        ->where("id_lt", "=", $request->get("op"))
        ->delete();

        Session::forget("LayoutTeso");


        $prueba = DB::table("bancos_l_tesoreria")
        ->get();

        return response()->json($prueba);
    }

    public function actualizarLayoutSAP(Request $request)
    {
        DB::table("bancos_l_SAP")
        ->where("id_ls", "=", $request->get("op"))
        ->update([
            "nombre" => $request->get("nombre"),
            "hoja_sap" => $request->get("hojaSap"),
            "hoja_bancos" => $request->get("hojaBancos"),
            "ID" => $request->get("id_cliente"),
            "FOLIO" => $request->get("clearing"),
            "MONEDAPAGO" => $request->get("moneda_pago"),
            "MONTOPAGO" => $request->get("monto_pago"),
            "TIPOCAMBIOP" => $request->get("tipocambio_pago"),
            "FOLIOS" => $request->get("folios"),
            "PARCIAL" => $request->get("parcialidad"),
            "TIPODOC" => $request->get("dtipo"),
            "FECHAPAGO" => $request->get("fecha_pago"),
            "NUMREGIDTRIB" => $request->get("numregidtrib"),
            "REFERENCE" => $request->get("reference"),
            "ASSIGNMENT" => $request->get("assignment"),
            "MONTOPAGOMXN" => $request->get("montomxn_pago"),
            "IMPUESTO" => $request->get("impuesto"),
            "USOCFDI" => $request->get("usocfdi"),//Aqui se modifico
            "TASAIVA" => $request->get("tasaiva"),//Aqui se modifico
            "TASARETENCION" => $request->get("tasaretencion"),//Aqui se modifico
        ]);

        DB::table("bancos_p_SAP")
        ->where("id_ls", "=", $request->get("op"))
        ->delete();

        Session::forget("LayoutSAP");

        $prueba = DB::table("bancos_l_SAP")
        ->get();

        return response()->json($prueba);
    }

    public function actualizarLayoutCredito(Request $request)
    {
        DB::table("bancos_l_credito")
        ->where("id_lc", "=", $request->get("op"))
        ->update([
            "nombre" => $request->get("nombre"),
            "folio" => $request->get("folio"),
            "clearing" => $request->get("clearing"),
            "parcialidad" => $request->get("parcialidad"),
            "moneda" => $request->get("moneda"),
            "tipo_cambio" => $request->get("tipo_cambio"),
            "impsaldoant" => $request->get("impsaldoant"),
            "imppagado" => $request->get("imppagado")
        ]);

        DB::table("bancos_p_credito")
        ->where("id_lc", "=", $request->get("op"))
        ->delete();

        Session::forget("LayoutCred");

        $prueba = DB::table("bancos_l_credito")
        ->get();

        return response()->json($prueba);
    }

    public function cancelarActualizacionTesoreria(Request $request)
    {
        DB::table("bancos_p_tesoreria")
        ->where("id_lt", "=", Session::get("LayoutTeso"))
        ->delete();
        
        Session::forget("LayoutTeso");
        Session::forget("actualiza");

        return response()->json([
            "respuesta" => 0
        ]);
    }

    public function cancelarActualizacionSAP(Request $request)
    {
        DB::table("bancos_p_SAP")
        ->where("id_ls", "=", Session::get("LayoutSAP"))
        ->delete();
        
        Session::forget("LayoutSAP");
        Session::forget("actualiza");

        return response()->json([
            "respuesta" => 0
        ]);
    }

    public function cancelarActualizacionCredito(Request $request)
    {
        DB::table("bancos_p_credito")
        ->where("id_lc", "=", Session::get("LayoutCred"))
        ->delete();
        
        Session::forget("LayoutCred");
        Session::forget("actualiza");

        return response()->json([
            "respuesta" => 0
        ]);
    }

}
