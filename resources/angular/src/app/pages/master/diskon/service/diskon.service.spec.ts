import { TestBed } from '@angular/core/testing';

import { DiskonService } from './diskon.service';

describe('DiskonService', () => {
  let service: DiskonService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(DiskonService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
