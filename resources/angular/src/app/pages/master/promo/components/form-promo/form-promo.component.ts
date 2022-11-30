import { Component, OnInit, Input, SimpleChange, Output, EventEmitter } from '@angular/core';
import { LandaService } from 'src/app/core/services/landa.service';
import { PromoService } from '../service/promo.service';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'form-promo',
  templateUrl: './form-promo.component.html',
  styleUrls: ['./form-promo.component.scss']
})
export class FormPromoComponent implements OnInit {
  @Input() itemId: number;
  @Output() afterSave  = new EventEmitter<boolean>();
  mode: string;
  tipe: any;
  formModel : {
    nama: string,
    type: string,
    nominal: number,
    diskon: number,
    kadaluarsa: number,
    foto?: string,
    fotoUrl? : string,
    syarat_ketentuan?: string
  }
  listItems:any;
  imgSrc:any;
  constructor(
    private promoService:PromoService,
    private landaService:LandaService,
    private modalService:NgbModal
  ) {}

  emptyForm(){
    this.mode = "add";
    this.formModel = {
        nama : '',
        type : '',
        nominal : 0,
        diskon : 0,
        kadaluarsa : 0,
        foto: '',
        fotoUrl : 'assets/img/no-image.png',
        syarat_ketentuan: ''
    }
    this.listItems = [
        {
            id: "1",
            nama: "1 hari"
        },
        {
            id: "7",
            nama: "7 hari"
        },
        {
            id: "30",
            nama: "30 hari"
        },
    ]
    if(this.itemId > 0){
        this.mode = 'edit';
        this.getItem(this.itemId);
    }
  }

  ngOnInit(): void {
  }

  ngOnChanges(changes: SimpleChange) {
    this.emptyForm();
}

  cekTipe(val:any){
    if(val === 'diskon'){
        this.formModel.nominal = null;
        this.formModel.type = 'diskon'
    }else{
        this.formModel.diskon = null;
        this.formModel.type = 'voucher'
    }
  }

  save(){
    if(typeof this.imgSrc !== undefined){
        this.formModel.foto = this.imgSrc;
    }else if(typeof this.imgSrc === undefined){
        this.imgSrc = ''
    }
    if(this.mode === 'add'){
        console.log(this.formModel.foto);
        this.promoService.createPromo(this.formModel).subscribe((res:any)=>{
            this.landaService.alertSuccess('Berhasil', res.message);
            this.afterSave.emit();
        },(err) => {
            this.landaService.alertError('Mohon maaf', err.error.errors)
        })
    } else {
        this.promoService.updatePromo(this.formModel).subscribe((res:any)=>{
            this.landaService.alertSuccess('Berhasil', res.message)
            this.afterSave.emit();
        },(err)=>{
            this.landaService.alertError('Mohon maaf', err.error.errors)
        })
    }
  }

  getItem(itemId){
    this.promoService.getPromoById(itemId).subscribe((res:any)=>{
        console.log(res.data);
        this.formModel = res.data
    }, (err)=>{
        console.log(err);
    })
  }

  back() {
    this.afterSave.emit();
}

readURL(event: any) {
    if (event.target.files && event.target.files[0]) {
      const file = event.target.files[0]
      const reader = new FileReader()
      reader.onload = (e) => (this.imgSrc = reader.result)

      reader.readAsDataURL(file)
    }
  }
}
