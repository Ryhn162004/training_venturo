import { Component, OnInit } from '@angular/core';
import { LandaService } from 'src/app/core/services/landa.service';
import { DiskonService } from '../../service/diskon.service';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { UserService } from '../../../users/services/user-service.service';
import { PromoService } from '../../../promo/components/service/promo.service';

@Component({
  selector: 'app-daftar-diskon',
  templateUrl: './daftar-diskon.component.html',
  styleUrls: ['./daftar-diskon.component.scss']
})
export class DaftarDiskonComponent implements OnInit {
  listDiskon = [];
  listUser = [];
  listPromo=[];
  filter = {
    nomor : 0,
    type :'diskon'
  }

  constructor(
    private landaService:LandaService,
    private diskonService:DiskonService,
    private modalService: NgbModal,
    private  promoService: PromoService,
    private userService: UserService
  ) { }

  ngOnInit(): void {
    this.getPromos()
    this.getDiskons()
  }

  getDiskons(){
    const params = {
        filter: JSON.stringify([])
    }
    this.diskonService.getDiskons(params).subscribe((res:any)=>{
        this.listDiskon = res.data.list;
        console.log(this.listDiskon);
    },(err)=>{
        console.log(err);
    })
  }

  getUsers(){
    const params = {
        filter: JSON.stringify(this.filter)
    }
    this.userService.getUsers(params).subscribe((res:any)=>{
        this.listUser = res.data.list
        // console.log(this.listUser);
    },(err)=>{
        console.log(err);
    })
  }

  getPromos(){
    const params = {
        filter: JSON.stringify(this.filter)
    }
    this.diskonService.getPromos(params).subscribe((res:any)=>{
        this.listPromo = res.data.list;
    }, (err)=>{
        console.log(err);
    })
  }

  update(user_id, diskon_id, promo_id, diskon_status, event:any){
    console.log(promo_id);
    if(diskon_id === 0){
        this.diskonService.createDiskon({id_user:user_id,id_promo:promo_id,status:1}).subscribe((res:any)=> {
            this.landaService.alertSuccess('Berhasil', res.message);
            this.getDiskons();
        },(err)=> {
            console.log(err);
        })
    }else{
        if(diskon_status === 0){
            this.diskonService.updateDiskon({id_diskon:diskon_id, status: 1}).subscribe((res:any)=>{
                this.landaService.alertSuccess('Berhasil', res.message);
            },(err)=>{
                console.log(err);
            })
        }else if(diskon_status === 1){
            this.diskonService.updateDiskon({id_diskon:diskon_id, status: 0}).subscribe((res:any)=>{
                this.landaService.alertSuccess('Berhasil', res.message);
            }, (err)=>{
                console.log(err);
            })
        }
    }
  }
}
