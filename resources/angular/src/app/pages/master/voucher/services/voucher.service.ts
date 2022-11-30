import { Injectable } from '@angular/core';
import { LandaService } from 'src/app/core/services/landa.service';

@Injectable({
  providedIn: 'root'
})
export class VoucherService {

  constructor(private landaService:LandaService) { }

  getVouchers(arrParameter){
    return this.landaService.DataGet('/v1/voucher', arrParameter);
  }

  getVoucherById(voucherId){
    return this.landaService.DataGet('/v1/voucher/' + voucherId);
  }

  createVoucher(payload){
    console.log(payload);
    return this.landaService.DataPost('/v1/voucher', payload);
  }

  updateVoucher(payload) {
    return this.landaService.DataPut('/v1/voucher', payload);
  }

 deleteVoucher(voucherId) {
        return this.landaService.DataDelete('/v1/voucher/' + voucherId);
   }
}
