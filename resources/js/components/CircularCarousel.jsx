import React, { useRef, useState, Children, forwardRef, useImperativeHandle } from 'react';
import './CircularCarousel.css';

export function lerp(start, stop, amt) {
  return (1 - amt) * start + amt * stop;
}

// Mengurangi ARC_SIZE untuk membuat jarak antar item lebih dekat
const ARC_SIZE = 150; // Dari 150 menjadi 100

function CircularCarouselComp(
  { onSelect, onSwapRight, onPointerDown, children },
  ref
) {
  const indexRef = useRef(0);
  const prevRef = useRef(0);
  const nextRef = useRef(0);
  const rendering = useRef(false);
  const [deg, setDeg] = useState(0);
  const [wrapper, setWrapper] = useState(null);

  const handleSetWrapper = (ref) => {
    setWrapper(ref);
  };

  prevRef.current = deg;

  function move() {
    const next = nextRef.current;
    const prev = prevRef.current;
    const deg = lerp(prev, next, 0.2);
    if (deg !== prev) {
      setDeg(deg);
      requestAnimationFrame(move);
    } else {
      rendering.current = false;
    }
    const index = Math.round(Math.abs(((deg / ARC_SIZE) * len) % len));
    if (index != indexRef.current) {
      indexRef.current = index;
      onSelect && onSelect(index);
    }
  }

  const len = Children.count(children);

  const onMouseDown = (e) => {
    const isTouch = e.type === "touchstart";
    let _deg = deg;

    onPointerDown && onPointerDown();

    const tryMove = (next) => {
      _deg = nextRef.current += next;
      _deg = nextRef.current = Math.min(_deg, 3);
      _deg = nextRef.current = Math.max(_deg, -ARC_SIZE + 3);
      if (!rendering.current) {
        rendering.current = true;
        requestAnimationFrame(move);
      }
    };

    const onMouseMove = ({ movementX }) => {
      tryMove(movementX / 30);
    };

    let prevTouchPageX;
    const onTouchMove = ({ touches }) => {
      const pageX = touches[0].pageX;
      if (prevTouchPageX) {
        const movementX = pageX - prevTouchPageX;
        tryMove(movementX / 10);
      }
      prevTouchPageX = pageX;
    };

    const onMouseUp = () => {
      document.removeEventListener("touchmove", onTouchMove);
      document.removeEventListener("mousemove", onMouseMove);
      document.removeEventListener("mouseup", onMouseUp);
      document.removeEventListener("touchend", onMouseUp);

      const angle = ARC_SIZE / len;
      const mod = _deg % angle;
      const diff = angle - Math.abs(mod);
      const sign = Math.sign(_deg);
      const max = angle * (len - 1);

      if (_deg > 0) {
        if (onSwapRight && indexRef.current === 0 && _deg > 2) {
          onSwapRight();
        }
        tryMove(-_deg);
      } else if (-_deg > max) {
        tryMove(-_deg - max);
      } else {
        const move = (diff <= angle / 2 ? diff : mod) * sign;
        tryMove(move);
      }
    };
    if (isTouch) {
      document.addEventListener("touchmove", onTouchMove);
      document.addEventListener("touchend", onMouseUp);
    } else {
      document.addEventListener("mousemove", onMouseMove);
      document.addEventListener("mouseup", onMouseUp);
    }
  };

  React.useEffect(() => {
    // No wheel event listener - scroll is disabled
    return () => {
      // Cleanup if needed
    };
  }, []);

  useImperativeHandle(
    ref,
    () => ({
      scrollTo(i) {
        const _deg = (-ARC_SIZE / len) * i;
        nextRef.current = _deg;
        if (!rendering.current) {
          rendering.current = true;
          requestAnimationFrame(move);
        }
      },
    }),
    [len],
  );

  return (
    <div className="circular-carousel-root" ref={handleSetWrapper}>
      <div
        className="circular-carousel-handle"
        onMouseDown={onMouseDown}
        onTouchStart={onMouseDown}
      >
        <div className="circular-carousel-center">
          <div
            className="circular-carousel-items"
            style={{ transform: `rotate(${deg}deg)` }}
          >
            {Children.map(children, (child, i) => (
              <div
                key={i}
                className="circular-carousel-item"
                style={{
                  transform: `translateX(-50%) rotate(${i * (ARC_SIZE / len)}deg)`,
                }}
              >
                {child}
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}

const CircularCarousel = forwardRef(CircularCarouselComp);

export default CircularCarousel;
