from pydantic import BaseModel
from typing import Optional, Literal

class Update(BaseModel):
    id: Optional[int]
    device_id: str
    units: int
    update_id: int
    status: Literal['pending', 'completed'] = 'pending' 