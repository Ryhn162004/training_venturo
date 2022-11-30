import { Component, OnInit,ViewChild } from '@angular/core';
import { VoucherService } from '../../services/voucher.service';
import Swal from 'sweetalert2';
import { DataTableDirective } from 'angular-datatables';
import { LandaService } from 'src/app/core/services/landa.service';
import { UserService } from '../../../users/services/user-service.service';
import { PromoService } from '../../../promo/components/service/promo.service';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-voucher',
  templateUrl: './voucher.component.html',
  styleUrls: ['./voucher.component.scss']
})
export class VoucherComponent implements OnInit {
    @ViewChild(DataTableDirective) dtElement: DataTableDirective;
    dtInstance: Promise<DataTables.Api>;
  listVoucher=[];
  listPromoVoucherType=[];
  listUsers=[];
  filter:{
    id_user :any,
    id_promo: any,
    nomor?: 0
    };
  dtOptions: any;
  titleCard:string;
  modelId:number;
  isOpenForm:boolean = false;
  constructor(
    private voucherService: VoucherService,
    private landaService: LandaService,
    private userService: UserService,
    private modalService: NgbModal,
    private promoService: PromoService
  ) {
    this.filter = {
        id_user : 0,
        id_promo: 0,
        nomor: 0
    }
  }

  ngOnInit(): void {
    this.getVouchers()
    this.getUsers()
    this.getPromoTypeVoucher()
  }

  getUsers(){
    const params = {
        filter: JSON.stringify(this.filter)
    }
    this.userService.getUsers(params).subscribe((res:any)=>{
        this.listUsers = res.data.list
    })
  }

  getPromoTypeVoucher(){
    const params = {
        filter: JSON.stringify(this.filter)
    }
    this.promoService.getPromos(params).subscribe((res:any)=>{
        this.listPromoVoucherType = res.data.list
    })
  }

  getVouchers(){
    this.dtOptions = {
        serverSide: true,
        processing: true,
        ordering: false,
        searching: false,
        pagingType: "full_numbers",
        pageLength: 5,
        ajax :(dataTablesParameters: any, callback)=>{
            const page = parseInt(dataTablesParameters.start) / parseInt(dataTablesParameters.length) + 1;
            const params = {
                filter: JSON.stringify(this.filter),
                offset: dataTablesParameters.start,
                limit: dataTablesParameters.length,
                page: page
            }
            this.voucherService.getVouchers(params).subscribe((res:any)=>{
                this.listVoucher = res.data.list
                callback({
                    recordsTotal: res.data.meta.total,
                    recordsFiltered: res.data.meta.total,
                    data: [],
                })
            },(err)=>{
                console.log(err);
            })
        }
    }
  }

  showForm(form){
    this.isOpenForm = form;
  }

  showSearch(modal){
    this.titleCard = "Form Pencarian"
    this.modalService.open(modal, { size: 'lg', backdrop: 'static' })
  }

  createVoucher(){
    this.titleCard = "Tambah Voucher";
    this.modelId = 0;
    this.showForm(true);
  }

  updateVoucher(voucherModel){
    this.titleCard = "Edit Voucher : " + voucherModel.user.nama;
    this.modelId = voucherModel.id_voucher;
    this.showForm(true);
  }

  reloadDataTable(): void {
    this.dtElement.dtInstance.then((dtInstance: DataTables.Api) => {
      dtInstance.draw()
    })
}

  hapusVoucher(voucherId){
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
            this.voucherService.deleteVoucher(voucherId).subscribe((res: any) => {
                this.reloadDataTable()
                this.getVouchers()
                this.landaService.alertSuccess('Berhasil', res.message)
            }, err => {
                console.log(err);
            });
        }
    });
  }

  search(){
    this.getVouchers()
    this.reloadDataTable()
  }

  resetFilter(){
    this.filter.id_user = null;
    this.filter.id_promo = null;
    this.getVouchers();
    this.reloadDataTable();
  }

}
