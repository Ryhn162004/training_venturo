import { Component, OnInit, ViewChild } from '@angular/core';
import { PromoService } from '../service/promo.service';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { LandaService } from 'src/app/core/services/landa.service';
import Swal from 'sweetalert2';
import { DataTableDirective } from 'angular-datatables';

@Component({
  selector: 'app-daftar-promo',
  templateUrl: './daftar-promo.component.html',
  styleUrls: ['./daftar-promo.component.scss']
})
export class DaftarPromoComponent implements OnInit {
  @ViewChild(DataTableDirective) dtElement: DataTableDirective;
  dtInstance: Promise<DataTables.Api>;
  listPromo:[];
  modelId: number = 0;
  isOpenForm:boolean=false;
  titleCard:string = '';
  dtOptions:any;
  filter:{
    nama: string
  }

  constructor(private promoService:PromoService, private modalService:NgbModal, private landaService:LandaService) {

    // membuat filter nama menjadi null diawal load
    this.filter = {
        nama : ''
    }
   }

  ngOnInit(): void {
    this.getPromo();
  }

  getPromo(){
    this.dtOptions = {
        serverSide: true,
            processing: true,
            ordering: false,
            searching: false,
            pagingType: "full_numbers",
            pageLength: 10,
            ajax :(dataTablesParameters: any, callback)=>{
                const page = parseInt(dataTablesParameters.start) / parseInt(dataTablesParameters.length) + 1;
                const params = {
                    filter: JSON.stringify(this.filter),
                    offset: dataTablesParameters.start,
                    limit: dataTablesParameters.length,
                    page: page
                }
                this.promoService.getPromos(params).subscribe((res:any)=>{
                    console.log(res.data.list);
                    this.listPromo = res.data.list

                    callback({
                        recordsTotal: res.data.meta.total,
                        recordsFiltered: res.data.meta.total,
                        data: [],
                    })
                }, (err:any) => {
                    console.log(err);
            })
        }
    }
  }

  showForm(form){
    this.isOpenForm = form
  }

  createPromo(){
    this.titleCard = "Tambah Promo",
    this.modelId = 0;
    this.showForm(true);
  }

  updatePromo(promoModel){
    this.titleCard = "Edit Promo : " + promoModel.nama,
    this.modelId = promoModel.id_promo
    this.showForm(true);
  }

  reloadDataTable(): void {
    this.dtElement.dtInstance.then((dtInstance: DataTables.Api) => {
      dtInstance.draw()
    })
}

  hapusPromo(promoId){
    Swal.fire({
        title: 'Apakah kamu yakin ?',
        text: 'Item tidak dapat melakukan pesanan setelah kamu menghapus datanya',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#34c38f',
        cancelButtonColor: '#f46a6a',
        confirmButtonText: 'Ya, Hapus data ini !',
    }).then((result) => {
        if (result.value) {
            this.promoService.deletePromo(promoId).subscribe((res: any) => {
                this.reloadDataTable()
                this.getPromo()
                this.landaService.alertSuccess('Berhasil', res.message)
            }, err => {
                console.log(err);
            });
        }
    });
  }

  showSearch(modal){
    this.titleCard = "Form Pencarian"
    this.modalService.open(modal, { size: 'lg', backdrop: 'static' })
  }

  search(){
    this.getPromo();
    this.reloadDataTable();
  }

  resetFilter(){
    this.filter.nama = null;
    this.getPromo();
    this.reloadDataTable();
  }

  formatRupiah(angka, prefix){
    var separator = "";
    var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
    split           = number_string.split(','),
    sisa             = split[0].length % 3,
    rupiah             = split[0].substr(0, sisa),
    ribuan             = split[0].substr(sisa).match(/\d{3}/gi);

    // tambahkan titik jika yang di input sudah menjadi angka ribuan
    if(ribuan){
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
  }


}
