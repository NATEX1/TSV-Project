<x-layout>
    <div class="container py-5">
        <h3 class="mb-4">แบบทดสอบการเป็น อสทก</h3>

        <form action="{{-- {{ route('quiz.submit') }} --}}" method="POST">
            @csrf

            {{-- คำถาม 1 --}}
            <div class="card mb-3 p-3">
                <h6>1. ท่านพร้อมที่จะทำงานอาสาเพื่อช่วยเหลือสังคมมากน้อยเพียงใด?</h6>
                <div>
                    <label><input type="radio" name="q1" value="มาก" required> มาก</label><br>
                    <label><input type="radio" name="q1" value="ปานกลาง"> ปานกลาง</label><br>
                    <label><input type="radio" name="q1" value="น้อย"> น้อย</label>
                </div>
            </div>

            {{-- คำถาม 2 --}}
            <div class="card mb-3 p-3">
                <h6>2. ท่านสามารถทำงานร่วมกับผู้อื่นได้ดีหรือไม่?</h6>
                <div>
                    <label><input type="radio" name="q2" value="ดีมาก" required> ดีมาก</label><br>
                    <label><input type="radio" name="q2" value="พอใช้"> พอใช้</label><br>
                    <label><input type="radio" name="q2" value="ไม่ค่อยได้"> ไม่ค่อยได้</label>
                </div>
            </div>

            {{-- คำถาม 3 --}}
            <div class="card mb-3 p-3">
                <h6>3. ท่านมีเวลาว่างสำหรับการปฏิบัติงานอาสาประมาณกี่ชั่วโมงต่อสัปดาห์?</h6>
                <input type="number" name="q3" class="form-control" min="0" placeholder="กรอกจำนวนชั่วโมง" required>
            </div>

            <button type="submit" class="btn btn-primary">ส่งคำตอบ</button>
        </form>
    </div>
</x-layout>
