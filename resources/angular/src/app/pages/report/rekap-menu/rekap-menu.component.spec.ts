import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RekapMenuComponent } from './rekap-menu.component';

describe('RekapMenuComponent', () => {
  let component: RekapMenuComponent;
  let fixture: ComponentFixture<RekapMenuComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RekapMenuComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RekapMenuComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
