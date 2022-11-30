import { Component, OnInit, EventEmitter, Input, Output, SimpleChange } from '@angular/core';
import { UserService } from '../../users/services/user-service.service';
import { LandaService } from 'src/app/core/services/landa.service';
import { PromoService } from '../../promo/components/service/promo.service';
import { VoucherService } from '../services/voucher.service';
import * as moment from 'moment';

@Component({
  selector: 'form-voucher',
  templateUrl: './form-voucher.component.html',
  styleUrls: ['./form-voucher.component.scss']
})
export class FormVoucherComponent implements OnInit {
  @Input() itemId: number;
  @Output() afterSave  = new EventEmitter<boolean>();

  listUsers=[];
  formatPeriodeAkhir:any = 'Pilih tanggal mulai';
  selectedUser:any;
  selectedVoucher:any;
  listVouchers=[];
  mode:string;
  formModel : {
    id_promo?: number,
    id_user?: number,
    type?: number,
    nominal?: number,
    info_voucher?: number,
    periode_mulai?: any,
    periode_selesai?: any,
    status?: number,
    catatan?: string,
  }
  filter = {
    nomor : 0,
    type :'voucher'
  }

  constructor(
    private userService:UserService,
    private promoService:PromoService,
    private landaService:LandaService,
    private voucherService:VoucherService
  ) {}

  ngOnInit(): void {
    this.getUsers(),
    this.getPromoTypeVoucher()
  }

  ngOnChanges(changes: SimpleChange) {
    this.emptyForm();
}

  emptyForm(){
    this.mode = "add";
    this.formModel = {
        id_promo: 0,
        id_user: 0,
        type: 0,
        nominal: 0,
        info_voucher: null,
        periode_mulai: null,
        periode_selesai: null,
        status: 0,
        catatan: '',
    }
    if(this.itemId > 0){
        this.mode = 'edit';
        this.getVoucher(this.itemId);
    }
  }

  cekStatus(val:any){
    if(val === 'aktif'){
        this.formModel.status = 1;
    }else{
        this.formModel.status = 0;
    }
  }

  getVoucher(itemId){
    this.voucherService.getVoucherById(itemId).subscribe((res:any)=>{
        console.log(res.data);
        this.formModel = res.data
        this.getSelectedUser()
        this.getSelectedVoucher()
    }, (err)=> {
        console.log(err);
    })
  }

  getUsers(){
    const params = {
        filter: JSON.stringify(this.filter)
    }
    this.userService.getUsers(params).subscribe((res:any)=>{
        this.listUsers = res.data.list
    })
  }

  getSelectedUser(){
    this.userService.getUserById(this.formModel.id_user).subscribe((res:any)=>{
        this.selectedUser = res.data
    })
  }

  getSelectedVoucher(){
      console.log(this.formModel.id_promo);
    this.promoService.getPromoById(this.formModel.id_promo).subscribe((res:any)=>{
        this.selectedVoucher = res.data
        this.formModel.nominal = res.data.nominal
        this.formModel.info_voucher = res.data.syarat_ketentuan
        console.log(this.formModel);
    })
  }

  selectDate(){
    const date = moment(this.formModel.periode_mulai).add('days', this.selectedVoucher.kadaluarsa).format('YYYY-MM-DD')
    this.formModel.periode_selesai = date;
    this.formatPeriodeAkhir = moment(this.formModel.periode_mulai).add('days', this.selectedVoucher.kadaluarsa).format('MM/DD/YYYY')
  }

  save(){
    if(this.mode === 'add'){
        this.voucherService.createVoucher(this.formModel).subscribe((res:any)=>{
            console.log(this.formModel);
            this.landaService.alertSuccess('Berhasil', res.message);
            this.afterSave.emit()
        },(err)=>{
            this.landaService.alertError('Mohon maaf', err.error.errors)
        })
    }else{
        this.voucherService.updateVoucher(this.formModel).subscribe((res:any)=>{
            this.landaService.alertSuccess('Berhasil', res.message)
            this.afterSave.emit()
        },(err)=>{
            this.landaService.alertError('Mohon maaf', err.error.errors)
        })
    }
  }

  getPromoTypeVoucher(){
    const params = {
        filter: JSON.stringify(this.filter)
    }
    this.promoService.getPromos(params).subscribe((res:any)=>{
        this.listVouchers = res.data.list
    })
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

  back() {
    this.afterSave.emit();
    }

}
